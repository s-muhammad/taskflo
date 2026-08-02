<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PhoneVerification;
use App\Services\AmootSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            auth()->user()->update([
                'last_activity' => now(),
            ]);
            return redirect()->intended('/');
        }
        return back()->withErrors([
            'phone' => 'اطلاعات وارد شده صحیح نمیباشد.',
        ]);
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $sms = new AmootSms();
        $apiCode = $sms->sendOtp($validated['phone']);

        if (!$apiCode) {
            return back()->withErrors(['phone' => 'ارسال پیامک با مشکل مواجه شد. لطفاً بعداً تلاش کنید.']);
        }

        PhoneVerification::where('phone', $validated['phone'])->where('type', 'register')->delete();

        PhoneVerification::create([
            'phone' => $validated['phone'],
            'code' => $apiCode,
            'type' => 'register',
            'data' => $validated,
            'expires_at' => now()->addMinutes(5),
        ]);

        session(['verify_phone' => $validated['phone'], 'verify_type' => 'register']);

        return redirect()->route('verify.phone.form');
    }

    public function verifyPhoneForm()
    {
        $phone = session('verify_phone');
        $type = session('verify_type', 'register');

        if (!$phone) {
            return redirect()->route('register');
        }

        return view('auth.verify-phone', compact('phone', 'type'));
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $phone = session('verify_phone');
        $type = session('verify_type', 'register');

        if (!$phone) {
            return redirect()->route('register');
        }

        $verification = PhoneVerification::where('phone', $phone)
            ->where('type', $type)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'کد نامعتبر یا منقضی شده است.']);
        }

        if ($type === 'register') {
            User::create([
                'name' => $verification->data['name'],
                'phone' => $verification->phone,
                'password' => Hash::make($verification->data['password']),
            ]);

            $verification->delete();
            session()->forget(['verify_phone', 'verify_type']);
            return redirect()->route('login')->with('success', 'ثبت‌نام با موفقیت انجام شد.');
        }

        if ($type === 'reset') {
            $verification->delete();
            session()->forget(['verify_phone', 'verify_type']);
            session(['reset_phone' => $phone]);
            return redirect()->route('reset.password.form');
        }

        return redirect('/');
    }

    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:users,phone',
        ]);

        $sms = new AmootSms();
        $apiCode = $sms->sendOtp($request->phone);

        if (!$apiCode) {
            return back()->withErrors(['phone' => 'ارسال پیامک با مشکل مواجه شد. لطفاً بعداً تلاش کنید.']);
        }

        PhoneVerification::where('phone', $request->phone)->where('type', 'reset')->delete();

        PhoneVerification::create([
            'phone' => $request->phone,
            'code' => $apiCode,
            'type' => 'reset',
            'expires_at' => now()->addMinutes(5),
        ]);

        session(['verify_phone' => $request->phone, 'verify_type' => 'reset']);

        return redirect()->route('verify.phone.form');
    }

    public function resetPasswordForm()
    {
        $phone = session('reset_phone');

        if (!$phone) {
            return redirect()->route('forgot.password.form');
        }

        return view('auth.reset-password', compact('phone'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $phone = session('reset_phone');

        if (!$phone) {
            return redirect()->route('forgot.password.form');
        }

        User::where('phone', $phone)->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget('reset_phone');

        return redirect()->route('login')->with('success', 'رمز عبور با موفقیت تغییر کرد.');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->intended('/');
    }
}
