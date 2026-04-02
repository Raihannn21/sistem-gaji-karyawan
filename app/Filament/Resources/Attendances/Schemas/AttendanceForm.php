<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('scan_masuk'),
                TimePicker::make('scan_pulang'),
                TextInput::make('total_jam_kerja')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_holiday')
                    ->required(),
            ]);
    }
}
