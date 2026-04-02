<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $karyawanAktif = Employee::where('is_active', true)->count();
        
        $hadirHariIni = Attendance::whereDate('tanggal', today())->count();
        
        // Cek total payroll / gaji yang di generate bulan ini
        $payrollBulanIni = Payroll::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->sum('grand_total');

        return [
            Stat::make('Karyawan Aktif', $karyawanAktif)
                ->description('Total karyawan perusahaan')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([7, 2, 10, 3, 15, 4, $karyawanAktif])
                ->color('success'),
                
            Stat::make('Karyawan Hadir Hari Ini', $hadirHariIni)
                ->description(now()->translatedFormat('d F Y'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([1, 4, 3, 2, 5, 8, $hadirHariIni])
                ->color('info'),
                
            Stat::make('Total Gaji Dibayarkan', 'Rp ' . number_format($payrollBulanIni, 0, ',', '.'))
                ->description('Pengeluaran pada ' . now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
