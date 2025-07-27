<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn(Reservation $record): bool => $record->status === 'pending' && auth()->user()->can('approveReservations'))
                ->action(function (Reservation $record) {
                    $record->update(['status' => 'approved', 'approved_by' => auth()->id()]);
                    \Filament\Notifications\Notification::make()
                        ->title('Reservation Approved')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('complete')
                ->label('Complete')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->visible(fn(Reservation $record): bool => $record->status === 'approved' && auth()->user()->can('completeReservations'))
                ->action(function (Reservation $record) {
                    $record->recalculatePaymentAmount();
                    $record->refresh();
                    // Check if total payments paid match payment_amount
                    $totalPaid = $record->payments()
                        ->whereIn('transaction_status', ['settlement', 'paid', 'capture'])
                        ->sum('amount');

                    if ($totalPaid < $record->payment_amount) {
                        \Filament\Notifications\Notification::make()
                            ->title('Cannot Complete Reservation')
                            ->body('Total paid amount (Rp' . number_format($totalPaid, 0, ',', '.') . ') does not match reservation amount (Rp' . number_format($record->payment_amount, 0, ',', '.') . ').')
                            ->danger()
                            ->send();
                        return; // Stop the action
                    }

                    $record->update(['status' => 'completed', 'completed_by' => auth()->id()]);
                    \Filament\Notifications\Notification::make()
                        ->title('Reservation Completed')
                        ->success()
                        ->send();
                })
        ];
    }
}
