<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
        'auth:sanctum',
    ])->get('/user', function (Request $request) {
        return $request->user();
    });

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Неверные данные.'],
        ]);
    }

    auth()->login($user);

    return response()->json(['message' => 'Успешный вход']);
});

Route::post('/logout', function (Request $request) {
    auth()->logout();
    return response()->json(['message' => 'Выход выполнен']);
});
