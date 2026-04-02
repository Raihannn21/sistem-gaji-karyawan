<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;
    protected string $view = 'filament.resources.settings.pages.edit-setting';

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction dihilangkan demi keamanan data master
        ];
    }
}
