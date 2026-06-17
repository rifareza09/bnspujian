<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // data pribadi
            $table->string('nama_lengkap');
            $table->text('alamat_ktp');
            $table->text('alamat_sekarang');
            $table->string('kecamatan');
            $table->foreignId('kabupaten_id')->constrained('kabupatens');
            $table->foreignId('provinsi_id')->constrained('provinsis');
            $table->string('telepon')->nullable();
            $table->string('hp');
            $table->string('email');

            // identitas
            $table->enum('kewarganegaraan', ['WNI Asli', 'WNI Keturunan', 'WNA'])->default('WNI Asli');
            $table->string('negara_asal')->nullable(); // kalau WNA
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('negara_lahir')->nullable(); // kalau lahir di luar negeri
            $table->enum('jenis_kelamin', ['Pria', 'Wanita']);
            $table->enum('status_menikah', ['Belum Menikah', 'Menikah', 'Lain-lain'])->default('Belum Menikah');
            $table->enum('agama', ['Islam', 'Katolik', 'Kristen', 'Hindu', 'Budha', 'Lain-lain']);

            // multimedia
            $table->string('foto')->nullable();
            $table->string('dokumen')->nullable();

            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
