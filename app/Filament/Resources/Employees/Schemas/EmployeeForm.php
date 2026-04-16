<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Select;
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
                Select::make('employment_status')
                    ->label('Status Kerja')
                    ->options([
                        Employee::STATUS_PHL => Employee::STATUS_PHL,
                        Employee::STATUS_PKWT => Employee::STATUS_PKWT,
                    ])
                    ->required()
                    ->native(false),
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
