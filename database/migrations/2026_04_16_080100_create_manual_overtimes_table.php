<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('jenis_lembur', ['biasa', 'libur']);
            $table->unsignedSmallInteger('jam_lembur')->comment('Jam lembur manual, wajib bilangan bulat');
            $table->string('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'tanggal', 'jenis_lembur'], 'manual_overtimes_unique_employee_date_type');
            $table->index(['tanggal', 'jenis_lembur'], 'manual_overtimes_date_type_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_overtimes');
    }
};
