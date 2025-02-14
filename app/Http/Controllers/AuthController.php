<?php
/**
 *  Created By : Yufan Amri
 *  email : yufan.amri@gmail.com
 *  2025
 *
 */
namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->locked_until && Carbon::parse($user->locked_until)->isFuture()) {
                return response()->json([
                    'message' => 'Your account is locked. Try again in ' . Carbon::parse($user->locked_until)->diffForHumans(),
                ], 403);
            }
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                $user->increment('failed_attempts');

                if ($user->failed_attempts >= 3) {
                    $user->update([
                        'locked_until' => Carbon::now()->addMinutes(30), // locked 30 minutes
                    ]);

                    return response()->json([
                        'message' => 'Too many attempts. Your account is locked for 30 minutes.',
                    ], 403);
                }

                return response()->json([
                    'message' => 'Wrong email or password. Attempt ' . $user->failed_attempts . ' of 3.',
                ], 401);
            }

            $user->update([
                'failed_attempts' => 0,
                'locked_until' => null,
            ]);

            return response()->json([
                'token' => $token,
                'message' => 'Login success',
                'data' => $user
            ]);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',          // At least 8 characters
                    'regex:/[A-Z]/',  // At least 1 uppercase letter
                    'regex:/[a-z]/',  // At least 1 lowercase letter
                    'regex:/[0-9]/',  // At least 1 number
                ],
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'The email must be a valid email address.',
                'email.unique' => 'The email has already been taken.',
                'password.required' => 'The password field is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.regex' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, and 1 number.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Create user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $user
        ], 201);
    }


    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => Auth::guard('api')->user(),
        ]);
    }
}
