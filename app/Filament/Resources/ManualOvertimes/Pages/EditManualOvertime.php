<?php

namespace App\Filament\Resources\ManualOvertimes\Pages;

use App\Filament\Resources\ManualOvertimes\ManualOvertimeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManualOvertime extends EditRecord
{
    protected static string $resource = ManualOvertimeResource::class;
    protected string $view = 'filament.resources.manual-overtimes.pages.manual-overtime-form';

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
