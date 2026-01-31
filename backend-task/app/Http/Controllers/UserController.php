<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Страница авторизации
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function login_page()
    {
        return view('user.login');
    }

    /**
     * Авторизация
     * @param  LoginUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            throw ValidationException::withMessages(['email' => 'Invalid data']);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Список студентов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::with('courses')->has('courses')->get();
        return view('user.index', compact('users'));
    }
}
