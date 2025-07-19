<?php

namespace App\Filament\Resources\PrescriptionItemResource\Pages;

use App\Filament\Resources\PrescriptionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrescriptionItem extends EditRecord
{
    protected static string $resource = PrescriptionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
