<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('ID Sistem (Kunci)')
                    ->disabled()
                    ->dehydrated(false)
                    ->required(),
                TextInput::make('label')
                    ->label('Nama Label')
                    ->required(),
                TextInput::make('value')
                    ->label('Nilai Parameter')
                    ->numeric()
                    ->required(),
                TextInput::make('description')
                    ->label('Keterangan Tambahan'),
            ]);
    }
}
