<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_no')->unique()->comment('Nomor urut dari fingerprint (Emp No.)');
            $table->string('no_id')->unique()->comment('ID karyawan dari fingerprint (No. ID)');
            $table->string('nik')->nullable()->comment('NIK karyawan jika ada');
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('departemen')->nullable();
            $table->string('no_hp')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
