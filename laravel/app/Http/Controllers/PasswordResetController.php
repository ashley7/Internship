<?php

namespace App\Http\Controllers;

use App\Mail\Resetpasswordmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    // ── Forgot Password ───────────────────────────────────────
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Always show success to prevent email enumeration
        if (!$user || !$user->is_active) {
            return back()->with('status', 'If that email exists in our system, a reset link has been sent.');
        }

        // Delete any existing token for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate token.
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Send mail
        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $request->email]);

        Mail::to($user->email)->send(new Resetpasswordmail($user, $resetUrl,$token,$request->email));

        return back()->with('status', 'A password reset link has been sent to your email address.');
    }

    // ── Reset Password ────────────────────────────────────────
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email'],
            'token'                 => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find the token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'This password reset link is invalid.']);
        }

        // Check token is not expired (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        // Verify token hash
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'This password reset link is invalid.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully. You can now sign in.');
    }
}