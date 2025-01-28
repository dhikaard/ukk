<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $otp = Str::random(6);
        $hashedOtp = Hash::make($otp);

        DB::table('otps')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $hashedOtp, 'created_at' => now(), 'updated_at' => now()]
        );

        Notification::route('mail', $request->email)->notify(new OtpNotification($otp));

        return response()->json(['message' => 'OTP telah dikirim ke email Anda.'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $otpRecord = DB::table('otps')->where('email', $request->email)->first();

        if (!$otpRecord || !Hash::check($request->otp, $otpRecord->otp)) {
            return response()->json(['message' => 'OTP tidak valid atau telah kedaluwarsa.'], 400);
        }

        DB::table('otps')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Email berhasil diverifikasi.'], 200);
    }
}
