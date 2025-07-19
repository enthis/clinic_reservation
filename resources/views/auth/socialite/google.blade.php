{{-- resources/views/auth/socialite/google.blade.php --}}

<div class="mt-4 text-center">
    <p class="text-sm text-gray-600 dark:text-gray-400">Or login with</p>
    <a
        href="{{ route('auth.google') }}"
        class="mt-2 inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700"
    >
        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.0003 4.75C14.0273 4.75 15.8013 5.486 17.1403 6.782L20.0003 3.922C18.0213 2.057 15.2453 1 12.0003 1C7.75732 1 4.00832 3.472 2.38632 7.014L5.61732 9.549C6.46032 7.318 9.07032 5.75 12.0003 5.75V4.75Z" fill="#EA4335"/>
            <path d="M23.0003 12.0003C23.0003 11.3463 22.9463 10.7043 22.8423 10.0773H12.0003V13.9233H18.7293C18.4323 15.6553 17.4133 17.1123 15.9393 18.0793L19.1713 20.6133C21.0163 18.8123 22.1803 16.5053 22.6503 14.0003C22.8423 13.3733 23.0003 12.7043 23.0003 12.0003Z" fill="#4285F4"/>
            <path d="M5.61732 14.451L2.38632 16.986C3.47032 19.217 5.75732 21 8.50032 21C11.6603 21 14.3393 19.897 16.1703 17.942L12.9393 15.408C12.0963 17.639 9.48632 19.207 6.55632 19.207C5.61732 19.207 4.73332 19.012 3.93932 18.65L5.61732 14.451Z" fill="#FBBC05"/>
            <path d="M12.0003 19.207C9.07032 19.207 6.46032 17.639 5.61732 15.408L2.38632 17.942C4.00832 21.484 7.75732 23.957 12.0003 23.957C15.2453 23.957 18.0213 22.9 20.0003 21.035L17.1403 18.175C15.8013 19.471 14.0273 20.207 12.0003 20.207V19.207Z" fill="#34A853"/>
        </svg>
        Login with Google
    </a>
</div>

