<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            var_dump($request);
            echo 'Неправильный логин или пароль';
        } else {
            return redirect()->intended('dashboard');
        }
    }

    public function register(Request $request)
    {
        $user = User::create([
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => Hash::make($request->get('password'))
        ]);

        Auth::loginUsingId($user->id);

        return redirect()->intended('dashboard');
    }
}
