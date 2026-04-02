<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('emp_no')
                    ->required(),
                TextInput::make('no_id')
                    ->required(),
                TextInput::make('nik'),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('departemen'),
                TextInput::make('no_hp'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
