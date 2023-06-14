<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // $code = Str::random(4);

        // $status = Password::sendResetLink($request->only('email'), function(){// (Message $message) use ($code) {
           // $message->subject('Reset Password Verification Code');
            //$message->line('Your verification code is: ' . $code);
        // });

        // return $status === Password::RESET_LINK_SENT
        //     ? response()->json(['message' => 'Reset link sent to your email.'], 200)
        //     : response()->json(['message' => 'Unable to send reset link.'], 400);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return [
                    'status' => __($status)
                ];
            }

            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);

    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required|string',
            'code' => 'required|string', // New verification code field
        ]);

        // Check if the verification code matches the stored code for the email
        $codeExists = DB::table('users')
            // ->where('email', $request->email)
            ->where('code', $request->code)
            ->exists();

        if (!$codeExists) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            // Delete the verification code from the password_resets table
            // DB::table('users')
            //     ->where('email', $request->email)
            //     ->delete();

            return response()->json(['message' => 'Password reset successful.'], 200);
        } else {
            return response()->json(['message' => 'Unable to reset password.'], 400);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }


}
