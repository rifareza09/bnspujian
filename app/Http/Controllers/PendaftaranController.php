<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Pendaftaran;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PendaftaranController extends Controller
{
    public function dashboard()
    {
        $pendaftaran = Auth::user()->pendaftaran;
        return view('mahasiswa.dashboard', compact('pendaftaran'));
    }

    public function create()
    {
        if (Auth::user()->pendaftaran) {
            return redirect()->route('mahasiswa.dashboard')->with('info', 'Anda sudah mendaftar.');
        }

        $provinsis = Provinsi::orderBy('nama')->get();
        return view('mahasiswa.pendaftaran.create', compact('provinsis'));
    }

    public function store(Request $request)
    {
        $timeStart = microtime(true);

        if (Auth::user()->pendaftaran) {
            return redirect()->route('mahasiswa.dashboard');
        }

        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'alamat_ktp'      => 'required|string',
            'alamat_sekarang' => 'required|string',
            'kecamatan'       => 'required|string|max:255',
            'provinsi_id'     => 'required|exists:provinsis,id',
            'kabupaten_id'    => 'required|exists:kabupatens,id',
            'hp'              => 'required|numeric|digits_between:10,15',
            'telepon'         => 'nullable|numeric',
            'email'           => 'required|email',
            'kewarganegaraan' => 'required|in:WNI Asli,WNI Keturunan,WNA',
            'negara_asal'     => 'nullable|string|max:100',
            'tanggal_lahir'   => 'required|date',
            'tempat_lahir'    => 'required|string|max:255',
            'negara_lahir'    => 'nullable|string|max:100',
            'jenis_kelamin'   => 'required|in:Pria,Wanita',
            'status_menikah'  => 'required|in:Belum Menikah,Menikah,Lain-lain',
            'agama'           => 'required|in:Islam,Katolik,Kristen,Hindu,Budha,Lain-lain',
            'foto'            => 'nullable|image|max:2048',
            'dokumen'         => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'hp.numeric'           => 'Nomor HP harus berupa angka.',
            'hp.digits_between'    => 'Nomor HP harus 10-15 digit.',
            'email.email'          => 'Format email tidak valid.',
            'foto.image'           => 'File foto harus berupa gambar.',
            'foto.max'             => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = $request->except(['foto', 'dokumen', '_token']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto', 'public');
        }

        if ($request->hasFile('dokumen')) {
            $data['dokumen'] = $request->file('dokumen')->store('dokumen', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['status']  = 'pending';

        Pendaftaran::create($data);

        $timeEnd = microtime(true);
        $executionTime = round(($timeEnd - $timeStart) * 1000, 2); // milliseconds
        \Log::info("Pendaftaran store() execution time: {$executionTime}ms");

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Pendaftaran berhasil dikirim!');
    }

    public function show()
    {
        $pendaftaran = Auth::user()->pendaftaran;
        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.create');
        }
        $pendaftaran->load(['provinsi', 'kabupaten']);
        return view('mahasiswa.pendaftaran.show', compact('pendaftaran'));
    }

    public function exportPdf()
    {
        $memoryStart = memory_get_usage() / 1024 / 1024; // MB

        $pendaftaran = Auth::user()->pendaftaran;
        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.dashboard');
        }
        $pendaftaran->load(['provinsi', 'kabupaten', 'user']);
        $pdf = Pdf::loadView('mahasiswa.pendaftaran.pdf', compact('pendaftaran'));

        $memoryEnd = memory_get_usage() / 1024 / 1024; // MB
        \Log::info("PDF Export Memory Usage: Start={$memoryStart}MB, End={$memoryEnd}MB, Delta=" . ($memoryEnd - $memoryStart) . "MB");

        return $pdf->download('bukti-pendaftaran-' . $pendaftaran->id . '.pdf');
    }

    // API: ambil kabupaten berdasarkan provinsi
    public function kabupatenByProvinsi($provinsiId)
    {
        $kabupatens = Kabupaten::where('provinsi_id', $provinsiId)->orderBy('nama')->get(['id', 'nama']);
        return response()->json($kabupatens);
    }
}
