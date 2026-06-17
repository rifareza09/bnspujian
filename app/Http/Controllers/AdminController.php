<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_mahasiswa'    => User::where('role', 'mahasiswa')->count(),
            'total_pendaftaran'  => Pendaftaran::count(),
            'pending'            => Pendaftaran::where('status', 'pending')->count(),
            'diterima'           => Pendaftaran::where('status', 'diterima')->count(),
        ];

        $pendaftaranTerbaru = Pendaftaran::with(['user', 'provinsi', 'kabupaten'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendaftaranTerbaru'));
    }

    // --- CRUD User ---

    public function users(Request $request)
    {
        $users = User::when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        })->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,mahasiswa',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function userEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,mahasiswa',
        ]);

        $data = $request->only('name', 'email', 'role');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate.');
    }

    public function userDestroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // --- Kelola Pendaftaran ---

    public function pendaftarans(Request $request)
    {
        $pendaftarans = Pendaftaran::with(['user', 'provinsi', 'kabupaten'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$request->search}%"));
            })
            ->latest()
            ->paginate(10);

        return view('admin.pendaftarans.index', compact('pendaftarans'));
    }

    public function pendaftaranShow(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['user', 'provinsi', 'kabupaten']);
        return view('admin.pendaftarans.show', compact('pendaftaran'));
    }

    public function pendaftaranEdit(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['provinsi', 'kabupaten']);
        return view('admin.pendaftarans.edit', compact('pendaftaran'));
    }

    public function pendaftaranUpdate(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $pendaftaran->update(['status' => $request->status]);
        return redirect()->route('admin.pendaftarans')->with('success', 'Status pendaftaran diupdate.');
    }

    public function pendaftaranDestroy(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();
        return back()->with('success', 'Data pendaftaran dihapus.');
    }
}
