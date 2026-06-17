<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id', 'nama_lengkap', 'alamat_ktp', 'alamat_sekarang',
        'kecamatan', 'kabupaten_id', 'provinsi_id', 'telepon', 'hp', 'email',
        'kewarganegaraan', 'negara_asal', 'tanggal_lahir', 'tempat_lahir',
        'negara_lahir', 'jenis_kelamin', 'status_menikah', 'agama',
        'foto', 'dokumen', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }
}
