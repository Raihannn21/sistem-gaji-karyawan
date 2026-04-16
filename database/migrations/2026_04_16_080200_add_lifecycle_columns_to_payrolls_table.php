<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->string('status_payroll', 10)
                ->default('draft')
                ->after('tanggal_selesai')
                ->comment('Status payroll: draft atau final');

            $table->timestamp('finalized_at')->nullable()->after('status_payroll');
            $table->foreignId('finalized_by')->nullable()->after('finalized_at')->constrained('users')->nullOnDelete();

            $table->index('status_payroll');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['finalized_by']);
            $table->dropIndex(['status_payroll']);
            $table->dropColumn(['status_payroll', 'finalized_at', 'finalized_by']);
        });
    }
};
