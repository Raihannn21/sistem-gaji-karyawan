<?php

namespace App\Filament\Resources\ManualOvertimes\Tables;

use App\Models\Employee;
use App\Models\ManualOvertime;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ManualOvertimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.nama')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.employment_status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('jenis_lembur')
                    ->label('Jenis')
                    ->formatStateUsing(fn (string $state) => $state === ManualOvertime::JENIS_LIBUR ? 'Lembur Libur' : 'Lembur Biasa')
                    ->badge(),
                TextColumn::make('jam_lembur')
                    ->label('Jam')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('creator.name')
                    ->label('Input Oleh')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('jenis_lembur')
                    ->label('Jenis Lembur')
                    ->options([
                        ManualOvertime::JENIS_BIASA => 'Lembur Biasa',
                        ManualOvertime::JENIS_LIBUR => 'Lembur Libur',
                    ]),
                SelectFilter::make('employment_status')
                    ->label('Status Karyawan')
                    ->relationship('employee', 'employment_status')
                    ->options([
                        Employee::STATUS_PHL => Employee::STATUS_PHL,
                        Employee::STATUS_PKWT => Employee::STATUS_PKWT,
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
