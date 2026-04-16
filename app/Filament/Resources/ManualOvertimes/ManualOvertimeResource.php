<?php

namespace App\Filament\Resources\ManualOvertimes;

use App\Filament\Resources\ManualOvertimes\Pages\CreateManualOvertime;
use App\Filament\Resources\ManualOvertimes\Pages\EditManualOvertime;
use App\Filament\Resources\ManualOvertimes\Pages\ListManualOvertimes;
use App\Filament\Resources\ManualOvertimes\Schemas\ManualOvertimeForm;
use App\Filament\Resources\ManualOvertimes\Tables\ManualOvertimesTable;
use App\Models\ManualOvertime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManualOvertimeResource extends Resource
{
    protected static ?string $model = ManualOvertime::class;

    protected static ?string $modelLabel = 'Lembur Manual';
    protected static ?string $pluralModelLabel = 'Input Lembur Manual';
    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return ManualOvertimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManualOvertimesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManualOvertimes::route('/'),
            'create' => CreateManualOvertime::route('/create'),
            'edit' => EditManualOvertime::route('/{record}/edit'),
        ];
    }
}
