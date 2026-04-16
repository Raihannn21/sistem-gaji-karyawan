<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('employment_status', 10)
                ->after('nama')
                ->comment('Status kerja: PHL atau PKWT');

            $table->index('employment_status');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['employment_status']);
            $table->dropColumn('employment_status');
        });
    }
};
