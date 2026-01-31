<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserApiController extends Controller
{
    /**
     * Вход
     * @param  LoginUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            throw ValidationException::withMessages(['email' => 'Invalid data']);
        }

        $token = auth()->user()->createToken('token')->plainTextToken;

        return response()->json(compact('token'));
    }

    /**
     * Регистрация
     * @param  StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        User::create($validated);
        return response()->noContent();
    }
}
