<?php

namespace App\Filament\Resources\DoctorNoteResource\Pages;

use App\Filament\Resources\DoctorNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctorNote extends CreateRecord
{
    protected static string $resource = DoctorNoteResource::class;
}
