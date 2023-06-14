<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetTokens;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CodeCheckRequest;


class CodeCheckController extends Controller
{
    public function __invoke(CodeCheckRequest $request)
    {
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return $this->jsonResponse(null, trans('passwords.code_is_expire'), 422);
        }

        return $this->jsonResponse(['code' => $passwordReset->code], trans('passwords.code_is_valid'), 200);
    //     $request->validate([
    //         'token' => 'required|string|exists:password_reset_tokens',
    //     ]);

    //     // find the code
    //     $passwordReset = PasswordResetTokens::firstWhere('token', $request->token);

    //     // check if it does not expired: the time is one hour
    //     if ($passwordReset->created_at > now()->addHour()) {
    //         $passwordReset->delete();
    //         return response(['message' => trans('passwords.code_is_expire')], 422);
    //     }

    //     return response([
    //         'token' => $passwordReset->token,
    //         'message' => trans('passwords.code_is_valid')
    //     ], 200);
    // }
    // public function __invokes(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required|string|exists:password_reset_tokens',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     // find the code
    //     $passwordReset = PasswordResetTokens::firstWhere('token', $request->token);

    //     // check if it does not expired: the time is one hour
    //     if ($passwordReset->created_at > now()->addHour()) {
    //         $passwordReset->delete();
    //         return response(['message' => trans('passwords.code_is_expire')], 422);
    //     }

    //     // find user's email
    //     $user = User::firstWhere('email', $passwordReset->email);

    //     // update user password
    //     $user->update($request->only('password'));

    //     // delete current code
    //     $passwordReset->delete();

    //     return response(['message' =>'password has been successfully reset'], 200);
    }

}
