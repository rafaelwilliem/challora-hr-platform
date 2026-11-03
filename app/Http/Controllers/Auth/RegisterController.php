<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public static function getActiveHrInviteCode(): string
    {
        try {
            $cached = Cache::store('database')->get('hr_invite_code');
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
        } catch (\Throwable $e) {
            // Fallback to default cache/config if database cache table isn't ready
        }

        return (string) Cache::get('hr_invite_code', config('auth.hr_invite_code', 'CHALLORA-HR-2026'));
    }

    public function showRegistrationForm()
    {
        $requiresHrInviteCode = User::where('role', UserRole::HR)->exists();

        return view('auth.register', [
            'requiresHrInviteCode' => $requiresHrInviteCode,
        ]);
    }

    public function register(Request $request)
    {
        $isHr = $request->input('role') === 'hr';
        $requiresHrInviteCode = User::where('role', UserRole::HR)->exists();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'in:user,hr'],
            'hr_invite_code' => [$isHr && $requiresHrInviteCode ? 'required' : 'nullable', 'string'],
        ]);

        if ($isHr && $requiresHrInviteCode) {
            $expectedCode = self::getActiveHrInviteCode();
            $providedCode = trim((string) $request->input('hr_invite_code'));

            if ($expectedCode === '' || !hash_equals($expectedCode, $providedCode)) {
                throw ValidationException::withMessages([
                    'hr_invite_code' => ['The provided HR invite code is invalid.'],
                ]);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isHr ? UserRole::HR : UserRole::USER,
        ]);

        auth()->login($user);

        return redirect($isHr ? '/hr/dashboard' : '/jobs');
    }
}
