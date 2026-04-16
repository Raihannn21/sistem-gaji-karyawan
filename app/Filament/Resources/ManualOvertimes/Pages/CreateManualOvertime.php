<?php

namespace App\Filament\Resources\ManualOvertimes\Pages;

use App\Filament\Resources\ManualOvertimes\ManualOvertimeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateManualOvertime extends CreateRecord
{
    protected static string $resource = ManualOvertimeResource::class;
    protected string $view = 'filament.resources.manual-overtimes.pages.manual-overtime-form';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }
}
