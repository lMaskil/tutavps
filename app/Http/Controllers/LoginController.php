<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() {
        return view('login_page');
    }
    // Обработка отправки формы входа
    public function store(Request $request)
    {
// 1. Валидация полей
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

// 2. Попытка авторизации
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
// Регенерация сессии для безопасности
            $request->session()->regenerate();

// Перенаправление на главную
            return redirect()->route('glitch.index');
        }

// 3. Возврат ошибки, если данные не совпали
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        // 1. Выход пользователя
        Auth::logout();

        // 2. Очистка сессии для безопасности
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Редирект на главную
        return redirect()->route('glitch.index');
    }

}

