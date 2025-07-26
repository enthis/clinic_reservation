<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 960px;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
        }
        .badge.bg-yellow-200 { background-color: #fefcbf; color: #92400e; } /* warning */
        .badge.bg-green-200 { background-color: #d1fae5; color: #065f46; } /* success */
        .badge.bg-red-200 { background-color: #fee2e2; color: #991b1b; } /* danger */
        .badge.bg-blue-200 { background-color: #bfdbfe; color: #1e40af; } /* info */
        .badge.bg-gray-200 { background-color: #e5e7eb; color: #4b5563; } /* gray/default */
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">
    <header class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="text-2xl font-bold text-gray-800">Clinic Reservation</a>
            <nav>
                <span class="text-gray-700 mr-4">Welcome, {{ Auth::user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-300">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="container mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center">Your Dashboard</h1>
            <p class="text-lg text-gray-700 mb-8 text-center">
                Here you can view your upcoming and past reservations.
            </p>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session()->has('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Reservations</h2>

            @if($reservations->count() > 0)
                <div class="overflow-x-auto mb-8">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Service</th>
                                <th class="py-3 px-6 text-left">Doctor</th>
                                <th class="py-3 px-6 text-left">Date & Time</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-left">Payment</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @foreach($reservations as $reservation)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left">{{ $reservation->id }}</td>
                                    <td class="py-3 px-6 text-left">{{ $reservation->service->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $reservation->doctor->name }}</td>
                                    <td class="py-3 px-6 text-left">
                                        {{ $reservation->scheduled_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($reservation->scheduled_time)->format('H:i') }}
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        <span class="badge {{
                                            match ($reservation->status) {
                                                'pending' => 'bg-yellow-200 text-yellow-800',
                                                'approved' => 'bg-green-200 text-green-800',
                                                'rejected' => 'bg-red-200 text-red-800',
                                                'completed' => 'bg-blue-200 text-blue-800',
                                                default => 'bg-gray-200 text-gray-800',
                                            }
                                        }}">{{ ucfirst($reservation->status) }}</span>
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        <span class="badge {{
                                            match ($reservation->payment_status) {
                                                'pending' => 'bg-yellow-200 text-yellow-800',
                                                'paid' => 'bg-green-200 text-green-800',
                                                'failed' => 'bg-red-200 text-red-800',
                                                default => 'bg-gray-200 text-gray-800',
                                            }
                                        }}">{{ ucfirst($reservation->payment_status) }}</span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">View Details</a>
                                        @if($reservation->payment_status === 'pending' && $reservation->status !== 'cancelled')
                                            <button
                                                onclick="initiateMidtransPayment({{ $reservation->id }})"
                                                class="ml-2 px-3 py-1 bg-green-500 text-white rounded-md text-xs hover:bg-green-600 transition duration-300"
                                            >
                                                Pay Now
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center mb-8">You have no reservations yet.</p>
            @endif

            <a href="{{ route('landing') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-300">
                Make a New Reservation
            </a>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-auto">
        <div class="container mx-auto px-4 text-center">
            &copy; {{ date('Y') }} Clinic Reservation. All rights reserved.
        </div>
    </footer>

    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
    <script type="text/javascript">
        async function initiateMidtransPayment(reservationId) {
            try {
                const response = await fetch(`/api/reservation/${reservationId}/pay-midtrans`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();

                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                         onSuccess: function(result){
                            alert("Payment success!");
                            console.log(result);
                            // Corrected route generation for finish, unfinish, error
                            window.location.href = "{{ route('midtrans.finish', ['reservation' => '__RESERVATION_ID__']) }}".replace('__RESERVATION_ID__', reservationId);
                        },
                        onPending: function(result){
                            alert("Waiting for your payment!");
                            console.log(result);
                            window.location.href = "{{ route('midtrans.unfinish', ['reservation' => '__RESERVATION_ID__']) }}".replace('__RESERVATION_ID__', reservationId);
                        },
                        onError: function(result){
                            alert("Payment failed!");
                            console.log(result);
                            window.location.href = "{{ route('midtrans.error', ['reservation' => '__RESERVATION_ID__']) }}".replace('__RESERVATION_ID__', reservationId);
                        },
                        onClose: function(){
                            alert('You closed the popup without finishing the payment');
                            window.location.href = "{{ route('midtrans.unfinish', ['reservation' => '__RESERVATION_ID__']) }}".replace('__RESERVATION_ID__', reservationId);
                        }
                    });
                } else if (data.error) {
                    alert('Payment initiation failed: ' + data.error);
                } else if (data.info) {
                    alert(data.info);
                }
            } catch (error) {
                console.error('Error fetching Snap Token:', error);
                alert('An error occurred while preparing payment. Please try again.');
            }
        }
    </script>
</body>
</html>
