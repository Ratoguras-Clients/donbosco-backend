<?php

namespace App\Http\Controllers\Auth\ForgotPassword;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserHasOtp;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    public function forgotOTP()
    {
        $validator = Validator::make(request()->all(), [
            'phone' => 'required|min:9|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->all()]);
        }

        do {
            $otp = rand(100000, 999999);
        } while (UserHasOtp::where('otp', $otp)->exists());

        $type = 'reset';
        $helper = new Helper();
        $message = 'Your OTP is ' . $otp;
        $helper->sms(request()->phone, $message, ['type' => $type, 'otp' => $otp]);

        return response()->json(['success' => true, 'message' => 'OTP sent successfully']);
    }

    public function reset_otp_verification(Request $request)
    {
        $otp = $request->otp;
        $phone = $request->phone;

        $verification = UserHasOtp::where('phone_number', $phone)
            ->where('type', 'reset')
            ->where('otp', $otp)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($verification) {
            $datas = UserHasOtp::where('phone_number', $phone)
                ->where('type', 'reset')
                ->delete();
            if ($datas) {
                return response()->json(['success' => true, 'message' => 'OTP Verified successfully']);
            }
        }

        return response()->json(['success' => false, 'message' => 'OTP Mismatched or Expired']);
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            '_token' => ['required'],
            'phone' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->all()]);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Phone number not found']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        event(new PasswordReset($user));

        return response()->json(['success' => true, 'message' => 'Password has been reset successfully!']);
    }
}
