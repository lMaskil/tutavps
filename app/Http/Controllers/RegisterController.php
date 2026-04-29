<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request; // ЭТА СТРОКА ИСПРАВИТ ОШИБКУ
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Показ страницы регистрации
    public function index()
    {
        return view('register_page');
    }

    // Обработка отправки формы регистрации
    public function store(Request $request)
    {
        // 1. Валидация данных
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // 2. Создание пользователя в PostgreSQL
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Автоматический вход в систему
        Auth::login($user);

        // 4. Перенаправление на главную
        return redirect()->route('glitch.index');
    }
}
