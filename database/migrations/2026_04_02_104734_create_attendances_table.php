<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('scan_masuk')->nullable();
            $table->time('scan_pulang')->nullable();
            $table->decimal('total_jam_kerja', 5, 2)->default(0)->comment('Berdasarkan jam di CSV');
            $table->boolean('is_holiday')->default(false)->comment('Penanda apakah ini lembur libur/hari besar');
            $table->timestamps();

            $table->unique(['employee_id', 'tanggal'], 'emp_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
