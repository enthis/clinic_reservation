<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Make a New Reservation</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <div class="flex justify-between items-center text-sm font-medium text-gray-500">
            <div class="{{ $currentStep >= 1 ? 'text-blue-600' : '' }}">
                <span class="block text-center text-lg font-semibold">1</span>
                Service
            </div>
            <div class="flex-1 border-t-2 {{ $currentStep >= 2 ? 'border-blue-600' : 'border-gray-300' }} mx-2"></div>
            <div class="{{ $currentStep >= 2 ? 'text-blue-600' : '' }}">
                <span class="block text-center text-lg font-semibold">2</span>
                Doctor
            </div>
            <div class="flex-1 border-t-2 {{ $currentStep >= 3 ? 'border-blue-600' : 'border-gray-300' }} mx-2"></div>
            <div class="{{ $currentStep >= 3 ? 'text-blue-600' : '' }}">
                <span class="block text-center text-lg font-semibold">3</span>
                Schedule
            </div>
            <div class="flex-1 border-t-2 {{ $currentStep >= 4 ? 'border-blue-600' : 'border-gray-300' }} mx-2"></div>
            <div class="text-gray-500 {{ $currentStep >= 4 ? 'text-blue-600' : '' }}">
                <span class="block text-center text-lg font-semibold">4</span>
                Confirm
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submitReservation">
        {{-- Step 1: Choose Service --}}
        @if ($currentStep === 1)
            <div class="space-y-4">
                <label for="service" class="block text-sm font-medium text-gray-700">Select Service:</label>
                <select
                    id="service"
                    wire:model.live="selectedServiceId"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                >
                    <option value="">-- Select a Service --</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} (Rp{{ number_format($service->price, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                @error('selectedServiceId') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        @endif

        {{-- Step 2: Choose Doctor --}}
        @if ($currentStep === 2)
            <div class="space-y-4">
                <label for="doctor" class="block text-sm font-medium text-gray-700">Select Doctor:</label>
                <select
                    id="doctor"
                    wire:model.live="selectedDoctorId"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                >
                    <option value="">-- Select a Doctor --</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->specialty }})</option>
                    @endforeach
                </select>
                @error('selectedDoctorId') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        @endif

        {{-- Step 3: Choose Day and Time --}}
        @if ($currentStep === 3)
            <div class="space-y-4">
                <label for="day_of_week" class="block text-sm font-medium text-gray-700">Select Day:</label>
                <select
                    id="day_of_week"
                    wire:model.live="selectedDayOfWeek"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                >
                    <option value="">-- Select a Day --</option>
                    @foreach ($daysOfWeek as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('selectedDayOfWeek') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                @if ($selectedDayOfWeek && count($availableTimeSlots) > 0)
                    <label for="time_slot" class="block text-sm font-medium text-gray-700 mt-4">Available Time Slots:</label>
                    <select
                        id="time_slot"
                        wire:model="selectedTimeSlot"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm"
                    >
                        <option value="">-- Select a Time Slot --</option>
                        @foreach ($availableTimeSlots as $slot)
                            <option value="{{ $slot->id }}">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                @if($slot->notes) ({{ $slot->notes }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('selectedTimeSlot') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @elseif ($selectedDayOfWeek && count($availableTimeSlots) === 0)
                    <p class="text-sm text-gray-600 mt-4">No available time slots for this doctor on the selected day.</p>
                @endif
            </div>
        @endif

        {{-- Step 4: Confirmation --}}
        @if ($currentStep === 4)
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirm Your Reservation</h3>
                <div class="bg-gray-50 p-4 rounded-md shadow-sm border border-gray-200">
                    <p class="text-gray-700"><span class="font-semibold">Service:</span> {{ $reservationDetails['service_name'] ?? 'N/A' }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Doctor:</span> {{ $reservationDetails['doctor_name'] ?? 'N/A' }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Day:</span> {{ $reservationDetails['day_name'] ?? 'N/A' }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Time:</span> {{ $reservationDetails['start_time'] ?? 'N/A' }} - {{ $reservationDetails['end_time'] ?? 'N/A' }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Price:</span> Rp{{ number_format($reservationDetails['price'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <p class="text-gray-600 text-sm mt-4">Please review your details before confirming.</p>
            </div>
        @endif

        {{-- Navigation Buttons --}}
        <div class="mt-8 flex justify-between">
            @if ($currentStep > 1 && $currentStep < 4)
                <button
                    type="button"
                    wire:click="previousStep"
                    class="px-6 py-3 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300"
                >
                    Previous
                </button>
            @endif

            @if ($currentStep < 4)
                <button
                    type="button"
                    wire:click="nextStep"
                    class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 {{ $currentStep === 1 ? 'ml-auto' : '' }}"
                >
                    Next
                </button>
            @elseif ($currentStep === 4)
                <button
                    type="submit"
                    class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 ml-auto"
                >
                    Confirm Reservation & Pay
                </button>
            @endif
        </div>
    </form>
</div>

