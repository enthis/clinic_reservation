<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log; // For logging errors
use Spatie\Permission\Models\Role; // Import Spatie Role model
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Support\Str; // Import Str facade

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        Log::debug('Redirecting to Google for OAuth...');
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
            Log::debug('Handling Google OAuth callback...');
            // Retrieve user information from Google
            $googleUser = Socialite::driver('google')->user();
            Log::debug('Google User Data: ' . $googleUser->email);

            // Find user by Google ID or email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            // If user exists
            if ($user) {
                Log::debug('User found: ' . $user->email);
                if (is_null($user->google_id)) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                    Log::debug('Updated google_id for existing user: ' . $user->email);
                }
                // Ensure user has a role, assign 'user' if not
                if (!$user->hasAnyRole(Role::all())) { // Check if user has any Spatie role
                    $userRole = Role::firstOrCreate(['name' => 'user']);
                    $user->assignRole($userRole);
                    Log::info('Assigned "user" role to existing user: ' . $user->email);
                }

                Auth::login($user);
                // Regenerate session ID after login for security and to ensure a fresh session for Sanctum
                request()->session()->regenerate(true);
                Log::info('User logged in via Google and session regenerated: ' . $user->email);

                // Generate a Sanctum API token and store it in the session
                $token = $user->createToken('google-login-token')->plainTextToken;
                session()->put('api_token', $token);
                Log::info('Sanctum API token generated and stored in session for user: ' . $user->email);
                if ($user->hasRole('user')) {
                    return redirect()->intended('/dashboard');
                } else {
                    return redirect()->route('filament.admin.auth.login');
                } // Redirect to dashboard or intended page
            } else {
                // If user does not exist, create a new user account
                Log::debug('Creating new user from Google data: ' . $googleUser->email);
                $password = (app()->environment('local') || app()->environment('testing'))
                    ? Hash::make('password')
                    : Hash::make(Str::random(16)); // Generate a 16-character random string and hash it

                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'password' => $password, // Use the generated/hashed password
                ]);

                // Assign default 'user' role from Spatie
                $userRole = Role::firstOrCreate(['name' => 'user']);
                $newUser->assignRole($userRole);
                Log::info('New user created and assigned "user" role: ' . $newUser->email);


                Auth::login($newUser);
                // Regenerate session ID after login for security and to ensure a fresh session for Sanctum
                request()->session()->regenerate(true);
                Log::info('New user logged in via Google and session regenerated: ' . $newUser->email);

                // Generate a Sanctum API token and store it in the session
                $token = $newUser->createToken('google-login-token')->plainTextToken;
                session()->put('api_token', $token);
                Log::info('Sanctum API token generated and stored in session for new user: ' . $newUser->email);

                return redirect()->intended('/dashboard');
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Google authentication failed: ' . $e->getMessage(), ['exception' => $e]);

            // Redirect back with an error message
            return redirect('/')->with('error', 'Unable to login with Google. Please try again.');
        }
    }
}
