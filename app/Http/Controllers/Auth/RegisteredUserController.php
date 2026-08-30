<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            // Admin gets full access automatically.
            // All other new users start with no access.
            'ai_workspace_access' => $request->email === 'zeelinks1slamabad@gmail.com',
            'api_access' => $request->email === 'zeelinks1slamabad@gmail.com',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Admin goes directly to Permissions.
        if ($user->email === 'zeelinks1slamabad@gmail.com') {
            return redirect()->route('permissions');
        }

        return redirect()->route('dashboard');
    }
}