<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('periode')->comment('Misal: Februari 2026');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('total_gaji_pokok', 15, 2)->default(0);
            $table->decimal('total_uang_lembur', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
