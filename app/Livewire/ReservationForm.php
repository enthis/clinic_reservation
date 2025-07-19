<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ReservationForm extends Component
{
    public $currentStep = 1;
    public $selectedServiceId;
    public $selectedDoctorId;
    public $selectedDayOfWeek;
    public $selectedTimeSlot;
    public $availableTimeSlots = [];

    public $reservationDetails = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $rules = [];

    protected $messages = [
        'selectedServiceId.required' => 'Please select a service.',
        'selectedServiceId.exists' => 'The selected service is invalid.',
        'selectedDoctorId.required' => 'Please select a doctor.',
        'selectedDoctorId.exists' => 'The selected doctor is invalid.',
        'selectedDayOfWeek.required' => 'Please select a day.',
        'selectedDayOfWeek.integer' => 'Invalid day selected.',
        'selectedTimeSlot.required' => 'Please select an available time slot.',
        'selectedTimeSlot.exists' => 'The selected time slot is invalid or unavailable.',
    ];

    // Helper array to convert day of week integer to string name
    private $dayNames = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function mount()
    {
        //
    }

    public function updatedSelectedDayOfWeek($value)
    {
        $this->availableTimeSlots = [];
        $this->selectedTimeSlot = null;
        $this->resetValidation('selectedTimeSlot');

        if ($this->selectedDoctorId && !is_null($value)) {
            $this->loadAvailableTimeSlots();
        }
    }

    public function updatedSelectedDoctorId()
    {
        $this->selectedDayOfWeek = null;
        $this->selectedTimeSlot = null;
        $this->availableTimeSlots = [];
        $this->resetValidation(['selectedDayOfWeek', 'selectedTimeSlot']);
    }

    private function loadAvailableTimeSlots()
    {
        if ($this->selectedDoctorId && !is_null($this->selectedDayOfWeek)) {
            $this->availableTimeSlots = DoctorSchedule::where('doctor_id', $this->selectedDoctorId)
                                                      ->where('day_of_week', $this->selectedDayOfWeek)
                                                      ->where('is_available', true)
                                                      ->orderBy('start_time')
                                                      ->get();
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->rules = ['selectedServiceId' => 'required|exists:services,id'];
        } elseif ($this->currentStep === 2) {
            $this->rules = ['selectedDoctorId' => 'required|exists:doctors,id'];
        } elseif ($this->currentStep === 3) {
            $this->rules = [
                'selectedDayOfWeek' => 'required|integer|min:0|max:6',
                'selectedTimeSlot' => 'required|exists:doctor_schedules,id',
            ];
        }

        $this->validate();

        $this->rules = [];
        $this->resetValidation();

        if ($this->currentStep === 3) {
            $service = Service::find($this->selectedServiceId);
            $doctor = Doctor::find($this->selectedDoctorId);
            $schedule = DoctorSchedule::find($this->selectedTimeSlot);

            if (!$service || !$doctor || !$schedule) {
                session()->flash('error', 'Error fetching reservation details. Please try again.');
                return;
            }

            $this->reservationDetails = [
                'service_name' => $service->name,
                'doctor_name' => $doctor->name,
                'day_name' => $this->dayNames[$this->selectedDayOfWeek] ?? 'N/A', // Use day name from helper
                'start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                'end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                'price' => $service->price,
            ];
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
        $this->resetValidation();
    }

    public function submitReservation()
    {
        $this->rules = [
            'selectedServiceId' => 'required|exists:services,id',
            'selectedDoctorId' => 'required|exists:doctors,id',
            'selectedDayOfWeek' => 'required|integer|min:0|max:6',
            'selectedTimeSlot' => 'required|exists:doctor_schedules,id',
        ];

        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to make a reservation.');
            return redirect()->route('auth.google');
        }

        try {
            $service = Service::find($this->selectedServiceId);
            $doctor = Doctor::find($this->selectedDoctorId);
            $schedule = DoctorSchedule::find($this->selectedTimeSlot);

            if (!$service || !$doctor || !$schedule) {
                session()->flash('error', 'Invalid selection. Please try again.');
                return;
            }

            $today = Carbon::today();
            // Convert integer day of week to string day name for Carbon::next()
            $dayNameForCarbon = strtolower($this->dayNames[$this->selectedDayOfWeek]);
            $scheduledDate = $today->copy()->next($dayNameForCarbon); // Corrected line

            if (!$schedule->is_available || Reservation::where('schedule_id', $schedule->id)
                                                      ->whereIn('status', ['pending', 'approved'])
                                                      ->exists()) {
                session()->flash('error', 'The selected time slot is no longer available. Please choose another.');
                return;
            }

            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'doctor_id' => $this->selectedDoctorId,
                'service_id' => $this->selectedServiceId,
                'schedule_id' => $this->selectedTimeSlot,
                'scheduled_date' => $scheduledDate,
                'scheduled_time' => $schedule->start_time,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_amount' => $service->price,
            ]);

            $schedule->update(['is_available' => false]);

            session()->flash('message', 'Reservation created successfully! Please proceed to payment.');

            return redirect()->route('reservation.pay.midtrans', $reservation->id);

        } catch (\Exception $e) {
            Log::error('Reservation creation failed: ' . $e->getMessage());
            session()->flash('error', 'An error occurred during reservation. Please try again.');
        }
    }

    public function render()
    {
        $services = Service::all();
        $doctors = Doctor::all();

        // Use the internal dayNames array for rendering the select options
        $daysOfWeekForView = $this->dayNames;

        return view('livewire.reservation-form', [
            'services' => $services,
            'doctors' => $doctors,
            'daysOfWeek' => $daysOfWeekForView,
        ]);
    }
}

