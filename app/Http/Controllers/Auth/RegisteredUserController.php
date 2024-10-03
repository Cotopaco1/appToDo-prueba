<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     * 
     * Valores que espera = email, name, password, password_confirmation
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'name'                  => 'El campo de nombre es requerido',
            'email'                 => 'El campo de email es requerido',
            'email.unique'          => 'El email ya esta registrado',
            'password.required'     => 'El campo de password es requerido',
            'password.confirmed'    => 'las passwords no coinciden',
            'password.min'          => 'La password debe contener minimo 8 caracteres'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $token = $user->createToken('personal_token', ['user']);

        $response = [
            'personal_token'    => $token->plainTextToken,
            'user'  => [
                'email' => $user->email,
                'name'  => $user->name,
            ]
        ];

        return response()->json($response, 201);
    }
}
