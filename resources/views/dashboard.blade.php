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
        <div class="container mx-auto bg-white p-8 rounded-lg shadow-lg text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-6">User Dashboard</h1>
            <p class="text-lg text-gray-700 mb-8">
                This is your personal dashboard. Here you can view your upcoming reservations, past records, and more.
            </p>
            <a href="{{ route('landing') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-300">
                Back to Reservation Page
            </a>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-auto">
        <div class="container mx-auto px-4 text-center">
            &copy; {{ date('Y') }} Clinic Reservation. All rights reserved.
        </div>
    </footer>
</body>
</html>

