<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            
            // Rekap kehadiran
            $table->integer('total_hadir')->default(0);
            $table->decimal('total_gaji_kehadiran', 15, 2)->default(0);
            
            // Rekap lembur biasa
            $table->integer('jam_lembur_biasa')->default(0);
            $table->decimal('total_gaji_lembur_biasa', 15, 2)->default(0);

            // Rekap lembur libur
            $table->integer('jam_lembur_libur')->default(0);
            $table->decimal('total_gaji_lembur_libur', 15, 2)->default(0);

            // Total per orang
            $table->decimal('total_gaji', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
