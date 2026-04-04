<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->decimal('jam_lembur_biasa', 5, 2)->default(0)->change();
            $table->decimal('jam_lembur_libur', 5, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->integer('jam_lembur_biasa')->default(0)->change();
            $table->integer('jam_lembur_libur')->default(0)->change();
        });
    }
};
