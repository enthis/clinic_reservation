<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log; // For logging errors

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        // Redirect to Google's OAuth consent screen
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google authentication.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            // Retrieve user information from Google
            $googleUser = Socialite::driver('google')->user();

            // Find user by Google ID or email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            // If user exists, update their Google ID if it's missing (e.g., if they previously registered with email)
            // Or log them in directly
            if ($user) {
                if (is_null($user->google_id)) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                Auth::login($user);
                return redirect()->intended('/dashboard'); // Redirect to dashboard or intended page
            } else {
                // If user does not exist, create a new user account
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(), // Mark email as verified since it's from Google
                    'password' => Hash::make(env('APP_ENV', 'local') ? 'password' : rand(1000000, 9999999)), // No password needed for Google login
                    'role' => 'user', // Default role for new users
                ]);

                // Log in the newly created user
                Auth::login($newUser);
                return redirect()->intended('/dashboard'); // Redirect to dashboard or intended page
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Google authentication failed: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect('/login')->with('error', 'Unable to login with Google. Please try again.');
        }
    }
}
