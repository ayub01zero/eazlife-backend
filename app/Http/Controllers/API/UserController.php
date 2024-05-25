<?php

namespace App\Http\Controllers\API;

use App\Helper\UserService;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $response = (new UserService($request->email, $request->password, $request->firstName, $request->lastName, $request->phoneNumber))->register($request->deviceName);
        return response()->json($response);
    }

    public function verify(Request $request)
    {
        $phoneNumber = $request->input('phoneNumber');
        $code = (int)$request->input('code');

        $user = User::where('phone_number', $phoneNumber)->latest()->first();

        if (!$user || $user->verification_code !== $code) {
            return response()->json(['status' => false, 'error' => 'Invalid verification code.']);
        } else {
            $user->verification_code = null;
            $user->save();

            return response()->json(['status' => true, 'message' => 'Phone number verified.']);
        }
    }

    public function login(Request $request)
    {
        $response = (new UserService($request->email, $request->password))->login($request->deviceName, $request->expoPushToken);
        return response()->json($response);
    }

    public function requestPasswordReset(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ['status' => false, 'error' => 'User not found'];
        }

        // Generate a unique verification code
        $code = rand(100000, 999999);

        // Store this code in the user's record with an expiration time
        $user->password_reset_code = $code;
        $user->password_reset_code_expires_at = now()->addMinutes(60);
        $user->save();

        Mail::to($user->email)->send(new PasswordResetCode($code));

        return ['status' => true, 'message' => 'Password reset code sent via Email'];
    }
    

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('password_reset_code', $request->code)
            ->where('password_reset_code_expires_at', '>', now())
            ->first();

        if (!$user) {
            return ['status' => false, 'error' => 'Invalid or expired reset code'];
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->password_reset_code = null;
        $user->password_reset_code_expires_at = null;
        $user->save();

        return ['status' => true, 'message' => 'Password has been reset'];
    }

    public function edit(Request $request)
    {
        $user = User::find($request->user()->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phoneNumber;

        $user->save();

        return response()->json(['status' => true, 'message' => 'Profile updated', 'user' => $user]);
    }

    // public function requestPasswordReset(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     $code = Str::random(6); // Generate a random code
    //     // Store this code in your database with an expiration time

    //     // Send the code to the user's email
    //     Mail::to($user->email)->send(new PasswordResetCode($code));

    //     return response()->json(['message' => 'Reset code sent to your email']);
    // }

    // // Method to handle the password reset using the code
    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'code' => 'required',
    //         'password' => 'required|confirmed'
    //     ]);

    //     // Verify the code and reset the password
    //     // ...
    // }
}
