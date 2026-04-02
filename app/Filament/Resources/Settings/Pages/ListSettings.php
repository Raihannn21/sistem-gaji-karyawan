<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;
    protected string $view = 'filament.resources.settings.pages.list-settings';

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Create dihilangkan agar user tidak bisa menambah setting manual
        ];
    }
}
