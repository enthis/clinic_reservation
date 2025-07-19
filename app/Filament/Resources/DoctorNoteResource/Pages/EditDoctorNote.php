<?php

namespace App\Filament\Resources\DoctorNoteResource\Pages;

use App\Filament\Resources\DoctorNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDoctorNote extends EditRecord
{
    protected static string $resource = DoctorNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
