<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(16))
            ]
        );

        # ini buat generate otp
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp
        ]);

        // Simpan ID user sementara di session
        session(['otp_user' => $user->id]);

        // Kirim OTP ke email
        Mail::send('emails.otp', ['name' => $user->name, 'otp' => $otp], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        return redirect('/verifikasi-otp');

    }

    public function showOtpForm()
    {
        if (!session('otp_user')) {
            return redirect()->route('login')->with('error', 'Session expired, please login again.');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $user = User::find(session('otp_user'));

        if (!$user) {
             return redirect()->route('login')->with('error', 'Session expired.');
        }

        if ($user->otp == $request->otp) {
            Auth::login($user);
            $user->update(['otp' => null]);
            session()->forget('otp_user');
            return redirect()->route('home');
        }

        return back()->with('error', 'OTP salah');
    }

    public function resendOtp()
    {
        $user = User::find(session('otp_user'));

        if (!$user) {
             return redirect()->route('login')->with('error', 'Session expired.');
        }

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);

        Mail::send('emails.otp', ['name' => $user->name, 'otp' => $otp], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        return back()->with('success', 'OTP baru telah dikirim.');
    }
}
