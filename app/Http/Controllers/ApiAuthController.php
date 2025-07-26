<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Ensure User model is imported
use Illuminate\Support\Facades\Log; // For logging

class ApiAuthController extends Controller
{
    /**
     * Handle user login and API token generation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        Log::debug('API Login attempt initiated.');

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('API Login failed: Invalid credentials for email: ' . $request->email);
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        // Revoke old tokens if any (optional, but good for security)
        $user->tokens()->delete();
        Log::debug('API Login: Old tokens revoked for user ID: ' . $user->id);

        // Create a new API token
        // You can specify abilities for the token, e.g., ['user:read', 'user:create']
        $token = $user->createToken('api-token')->plainTextToken;
        Log::info('API Login successful for user ID: ' . $user->id . '. Token generated.');

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->only('id', 'name', 'email'), // Return basic user info
            'token' => $token,
        ]);
    }

    /**
     * Handle user logout and API token revocation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();
        Log::info('API Logout successful for user ID: ' . $request->user()->id . '. Current token revoked.');

        return response()->json([
            'message' => 'Successfully logged out and token revoked.',
        ]);
    }
}
