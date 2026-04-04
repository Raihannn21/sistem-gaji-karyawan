<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Mengarahkan tampilan Dashboard ke file Blade kustom kita
    protected string $view = 'filament.pages.dashboard';

    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?string $breadcrumb = 'Dashboard';
}
