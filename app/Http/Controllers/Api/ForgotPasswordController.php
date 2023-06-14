<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        ResetCodePassword::where('email', $request->email)->delete();

        $codeData = ResetCodePassword::create($request->data());

        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return $this->jsonResponse(null, trans('passwords.sent'), 200);
    }
}
