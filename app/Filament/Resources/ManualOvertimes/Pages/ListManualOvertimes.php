<?php

namespace App\Filament\Resources\ManualOvertimes\Pages;

use App\Filament\Resources\ManualOvertimes\ManualOvertimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManualOvertimes extends ListRecords
{
    protected static string $resource = ManualOvertimeResource::class;
    protected string $view = 'filament.resources.manual-overtimes.pages.list-manual-overtimes';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
