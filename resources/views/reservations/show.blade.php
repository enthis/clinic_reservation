<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
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
                @auth
                    <span class="text-gray-700 mr-4">Welcome, {{ Auth::user()->name }}!</span>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-4">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-300">Logout</button>
                    </form>
                @else
                    <a href="{{ route('auth.google') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">Login with Google</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="container mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Reservation Details #{{ $reservation->id }}</h1>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-lg text-gray-700 mb-8">
                <div><span class="font-semibold">Service:</span> {{ $reservation->service->name }}</div>
                <div><span class="font-semibold">Doctor:</span> {{ $reservation->doctor->name }}</div>
                <div><span class="font-semibold">Scheduled Date:</span> {{ $reservation->scheduled_date->format('M d, Y') }}</div>
                <div><span class="font-semibold">Scheduled Time:</span> {{ \Carbon\Carbon::parse($reservation->scheduled_time)->format('H:i') }}</div>
                <div><span class="font-semibold">Reservation Status:</span> <span class="badge {{
                    match ($reservation->status) {
                        'pending' => 'bg-yellow-200 text-yellow-800',
                        'approved' => 'bg-green-200 text-green-800',
                        'rejected' => 'bg-red-200 text-red-800',
                        'completed' => 'bg-blue-200 text-blue-800',
                        default => 'bg-gray-200 text-gray-800',
                    }
                }}">{{ ucfirst($reservation->status) }}</span></div>
                <div><span class="font-semibold">Overall Payment Status:</span> <span class="badge {{
                    match ($reservation->payment_status) {
                        'pending' => 'bg-yellow-200 text-yellow-800',
                        'paid' => 'bg-green-200 text-green-800',
                        'failed' => 'bg-red-200 text-red-800',
                        default => 'bg-gray-200 text-gray-800',
                    }
                }}">{{ ucfirst($reservation->payment_status) }}</span></div>
                <div><span class="font-semibold">Amount:</span> Rp{{ number_format($reservation->payment_amount, 0, ',', '.') }}</div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Payment History</h2>
            @if($reservation->payments)
            @if($reservation->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Order ID</th>
                                <th class="py-3 px-6 text-left">Gateway</th>
                                <th class="py-3 px-6 text-left">Method</th>
                                <th class="py-3 px-6 text-right">Amount</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-left">Time</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @foreach($reservation->payments->sortByDesc('created_at') as $payment)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $payment->order_id }}</td>
                                    <td class="py-3 px-6 text-left">{{ ucfirst($payment->payment_gateway) }}</td>
                                    <td class="py-3 px-6 text-left">{{ $payment->payment_method ? ucfirst($payment->payment_method) : 'N/A' }}</td>
                                    <td class="py-3 px-6 text-right">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-6 text-left">
                                        <span class="badge {{
                                            match (true) {
                                                in_array($payment->transaction_status, ['settlement', 'paid', 'capture']) => 'bg-green-200 text-green-800',
                                                in_array($payment->transaction_status, ['pending', 'challenge']) => 'bg-yellow-200 text-yellow-800',
                                                in_array($payment->transaction_status, ['deny', 'expire', 'cancel', 'failed']) => 'bg-red-200 text-red-800',
                                                default => 'bg-gray-200 text-gray-800',
                                            }
                                        }}">{{ ucfirst($payment->transaction_status) }}</span>
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $payment->transaction_time ? $payment->transaction_time->format('M d, Y H:i') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center">No payment records found for this reservation yet.</p>
            @endif
            @endif


            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-300">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-auto">
        <div class="container mx-auto px-4 text-center">
            &copy; {{ date('Y') }} Clinic Reservation. All rights reserved.
        </div>
    </footer>
</body>
</html>
