<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Log tous les inputs reçus
            Log::info('Registration attempt', $request->all());
            $request->validate([
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'adresse' => ['required', 'min:3', 'max:255'],
                'telephone' => [
                    'required',
                    'string',
                    'regex:/^\d{9}$/',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/^\d{9}$/', $value)) {
                            $fail("Le numéro de téléphone doit comporter 9 chiffres.");
                        }
                    },
                ],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'prenom' => $request->firstname,
                'nom' => $request->lastname,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('user');

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            // Log détaillé de toute exception
            Log::error('Registration error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Rediriger avec un message d'erreur
            return back()->withErrors(['msg' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.'])
                ->withInput();
        }
    }
}
