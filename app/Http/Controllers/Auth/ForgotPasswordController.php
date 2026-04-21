<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate a simple token (in a real app, use password_resets table)
        $token = \Illuminate\Support\Str::random(60);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => \Hash::make($token), 'created_at' => now()]
        );

        $link = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // For demo purposes, we pass the link back to the view
        return back()->with('status', 'We have emailed your password reset link!')->with('demo_link', $link);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $record = \DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($record && \Hash::check($request->token, $record->token)) {
            $user = \App\Models\User::where('email', $request->email)->first();
            $user->password = \Hash::make($request->password);
            $user->save();

            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('status', 'Your password has been reset!');
        }

        return back()->withErrors(['email' => 'This password reset token is invalid.']);
    }
}
