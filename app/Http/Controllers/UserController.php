<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Создание пользователя
     *
     * @param CreateUserRequest $request "*"
     * @return JsonResponse
     */
    public static function addUser(CreateUserRequest $request): JsonResponse
    {
        $user = [
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        try {
            User::query()->create($user);
            return response()->json(['message' => 'Пользователь успешно создан'], 201);
        } catch (\Exception $e){
            return response()->json(['message' => 'Ошибка создания пользователя', 'error' => $e->getMessage()], 500);
        }
    }
}
