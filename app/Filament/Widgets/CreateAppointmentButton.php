<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Support\Enums\Alignment;
use Filament\Actions\Action;

class CreateAppointmentButton extends Widget
{
    protected static string $view = 'filament.widgets.create-appointment-button';

    protected static ?int $sort = -1; // Show at top

    protected static ?string $heading = null;

    protected int | string | array $columnSpan = 'full';
}
