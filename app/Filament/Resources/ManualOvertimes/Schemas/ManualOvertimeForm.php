<?php

namespace App\Filament\Resources\ManualOvertimes\Schemas;

use App\Models\Employee;
use App\Models\ManualOvertime;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ManualOvertimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->options(fn () => Employee::query()->where('is_active', true)->orderBy('nama')->pluck('nama', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                DatePicker::make('tanggal')
                    ->required(),
                Select::make('jenis_lembur')
                    ->label('Jenis Lembur')
                    ->options([
                        ManualOvertime::JENIS_BIASA => 'Lembur Biasa',
                        ManualOvertime::JENIS_LIBUR => 'Lembur Libur',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('jam_lembur')
                    ->label('Jam Lembur (Bulat)')
                    ->numeric()
                    ->minValue(1)
                    ->step(1)
                    ->rules(['integer', 'min:1'])
                    ->required(),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->maxLength(255),
            ]);
    }
}
