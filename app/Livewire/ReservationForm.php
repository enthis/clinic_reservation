<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Reservation;
use App\Models\User; // Ensure User model is imported
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Added for logging errors

class ReservationForm extends Component
{
    public $currentStep = 1;
    public $selectedServiceId;
    public $selectedDoctorId;
    public $selectedDayOfWeek;
    public $selectedTimeSlot; // This will store the actual schedule ID
    public $availableTimeSlots = []; // Stores DoctorSchedule models for the selected day

    // Reservation confirmation details
    public $reservationDetails = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    // Validation rules for each step
    public function getRules()
    {
        if ($this->currentStep === 1) {
            return [
                'selectedServiceId' => 'required|exists:services,id',
            ];
        } elseif ($this->currentStep === 2) {
            return [
                'selectedDoctorId' => 'required|exists:doctors,id',
            ];
        } elseif ($this->currentStep === 3) {
            return [
                'selectedDayOfWeek' => 'required|integer|min:0|max:6',
                'selectedTimeSlot' => 'required|exists:doctor_schedules,id',
            ];
        }

        return [];
    }


    // Messages for validation
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

    public function mount()
    {
        // Initialize with default values or fetched data if needed
    }

    public function updatedSelectedDayOfWeek($value)
    {
        $this->availableTimeSlots = []; // Reset available time slots
        $this->selectedTimeSlot = null; // Clear selected time slot

        if ($this->selectedDoctorId && !is_null($value)) {
            $this->loadAvailableTimeSlots();
        }
    }

    public function updatedSelectedDoctorId()
    {
        $this->selectedDayOfWeek = null; // Reset day selection
        $this->selectedTimeSlot = null; // Reset time slot selection
        $this->availableTimeSlots = []; // Clear available time slots
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
        $this->validate();

        if ($this->currentStep === 2) {
            // After selecting doctor, populate available days
            // No specific action needed here, as day selection is dynamic in step 3
        } elseif ($this->currentStep === 3) {
            // After selecting time slot, prepare reservation details for confirmation
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
                'day_name' => $schedule->day_name, // Using the accessor
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
    }

    public function submitReservation()
    {
        $this->validate(); // Validate step 3 again before final submission

        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to make a reservation.');
            return redirect()->route('auth.google'); // Redirect to login
        }

        try {
            $service = Service::find($this->selectedServiceId);
            $doctor = Doctor::find($this->selectedDoctorId);
            $schedule = DoctorSchedule::find($this->selectedTimeSlot);

            if (!$service || !$doctor || !$schedule) {
                session()->flash('error', 'Invalid selection. Please try again.');
                return;
            }

            // Calculate a future date based on the selected day of the week
            // Find the next occurrence of the selected day of the week
            $today = Carbon::today();
            $scheduledDate = $today->copy()->next($this->selectedDayOfWeek);

            // Ensure the schedule is still available and not already booked
            if (!$schedule->is_available || Reservation::where('schedule_id', $schedule->id)
                ->whereIn('status', ['pending', 'approved'])
                ->exists()
            ) {
                session()->flash('error', 'The selected time slot is no longer available. Please choose another.');
                return;
            }

            // Create the reservation
            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'doctor_id' => $this->selectedDoctorId,
                'service_id' => $this->selectedServiceId,
                'schedule_id' => $this->selectedTimeSlot,
                'scheduled_date' => $scheduledDate, // Calculated future date
                'scheduled_time' => $schedule->start_time, // Use start time from schedule
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_amount' => $service->price,
            ]);

            // Mark the schedule as unavailable
            $schedule->update(['is_available' => false]);

            session()->flash('message', 'Reservation created successfully! Please proceed to payment.');

            // Redirect to Midtrans payment initiation
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

        // Days of week for selection
        $daysOfWeek = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('livewire.reservation-form', [
            'services' => $services,
            'doctors' => $doctors,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }
}
