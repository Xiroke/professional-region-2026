<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login_page()
    {
        return view('user.login');
    }

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            throw ValidationException::withMessages(['email' => 'Invalid data']);
        }

        $token = auth()->user()->createToken('token')->plainTextToken;

        return response()->json(compact('token'));
    }

    public function index()
    {
        return User::all();
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validated();
        User::create($validated);
        return response()->noContent();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function edit(User $user)
    {
        return view('user.update', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);
        return response()->noContent();
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
