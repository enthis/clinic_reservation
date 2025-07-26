<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clinic Reservation</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
        }
        .container {
            max-width: 960px;
        }
    </style>
    @livewireStyles
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    {{-- Navigation for unauthenticated users --}}
                    {{-- The login forms are now directly in the main content area --}}
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="container mx-auto bg-white p-8 rounded-lg shadow-lg text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Book Your Appointment Today!</h1>
            <p class="text-lg text-gray-700 mb-8">
                Easily schedule your clinic visits with our simple online reservation system.
                Choose your service, select a doctor, and pick an available time slot.
            </p>

            @auth
                <p class="text-xl text-gray-800 mb-6">You are logged in. Ready to make a reservation?</p>
                {{-- This is where we'll integrate the Livewire reservation component --}}
                <div class="mt-8">
                    @livewire('reservation-form')
                </div>
            @else
                <p class="text-xl text-gray-800 mb-6">Please log in to start your reservation.</p>

                <div class="flex flex-col md:flex-row justify-center items-center gap-6 mt-8">
                    {{-- Google Login Button --}}
                    <a href="{{ route('auth.google') }}" class="inline-flex items-center justify-center px-8 py-4 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-300 transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.0003 4.75C14.0273 4.75 15.8013 5.486 17.1403 6.782L20.0003 3.922C18.0213 2.057 15.2453 1 12.0003 1C7.75732 1 4.00832 3.472 2.38632 7.014L5.61732 9.549C6.46032 7.318 9.07032 5.75 12.0003 5.75V4.75Z" fill="#EA4335"/>
                            <path d="M23.0003 12.0003C23.0003 11.3463 22.9463 10.7043 22.8423 10.0773H12.0003V13.9233H18.7293C18.4323 15.6553 17.4133 17.1123 15.9393 18.0793L19.1713 20.6133C21.0163 18.8123 22.1803 16.5053 22.6503 14.0003C22.8423 13.3733 23.0003 12.7043 23.0003 12.0003Z" fill="#4285F4"/>
                            <path d="M5.61732 14.451L2.38632 16.986C3.47032 19.217 5.75732 21 8.50032 21C11.6603 21 14.3393 19.897 16.1703 17.942L12.9393 15.408C12.0963 17.639 9.48632 19.207 6.55632 19.207C5.61732 19.207 4.73332 19.012 3.93932 18.65L5.61732 14.451Z" fill="#FBBC05"/>
                            <path d="M12.0003 19.207C9.07032 19.207 6.46032 17.639 5.61732 15.408L2.38632 17.942C4.00832 21.484 7.75732 23.957 12.0003 23.957C15.2453 23.957 18.0213 22.9 20.0003 21.035L17.1403 18.175C15.8013 19.471 14.0273 20.207 12.0003 20.207V19.207Z" fill="#34A853"/>
                        </svg>
                        Login with Google
                    </a>

                    <div class="text-gray-500 font-semibold">OR</div>

                    {{-- API Login Form --}}
                    <div id="api-login-form" class="bg-gray-50 p-6 rounded-lg shadow-inner flex-1 max-w-xs">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Login with Email</h2>
                        <form id="apiLoginForm" onsubmit="handleApiLogin(event)">
                            @csrf
                            <div class="mb-4 text-left">
                                <label for="api_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input
                                    type="email"
                                    id="api_email"
                                    name="email"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                            </div>
                            <div class="mb-6 text-left">
                                <label for="api_password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input
                                    type="password"
                                    id="api_password"
                                    name="password"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                            </div>
                            <button
                                type="submit"
                                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-300"
                            >
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-auto">
        <div class="container mx-auto px-4 text-center">
            &copy; {{ date('Y') }} Clinic Reservation. All rights reserved.
        </div>
    </footer>

    @livewireScripts
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        // Function to handle API login
        async function handleApiLogin(event) {
            event.preventDefault(); // Prevent default form submission

            const form = event.target;
            const email = form.api_email.value;
            const password = form.api_password.value;
            const csrfToken = form._token.value; // Get CSRF token from the form

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) { // Check for 2xx status codes
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Redirecting to your dashboard...',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '{{ route('dashboard') }}'; // Redirect to dashboard
                    });
                } else {
                    // API login failed
                    const errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Login failed. Please check your credentials.');
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: errorMessage,
                    });
                }
            } catch (error) {
                console.error('API Login Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'An unexpected error occurred during login. Please try again.',
                });
            }
        }
    </script>
</body>
</html>
