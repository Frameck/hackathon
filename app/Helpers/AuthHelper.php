<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthHelper
{
    public static function generateApiToken(array $data): JsonResponse
    {
        $isLogged = auth()->attempt($data);

        if (!$isLogged) {
            return response()->json([
                'message' => 'User could not be logged correctly',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()->where('email', $data['email'])->first();

        if ($user->tokens->isNotEmpty()) {
            return response()->json([
                'message' => 'User already has a token',
            ], Response::HTTP_CONFLICT);
        }

        $token = $user->createToken(md5($data['email']));

        return response()->json([
            'token' => $token->plainTextToken,
        ], Response::HTTP_CREATED);
    }

    public static function getValidationRules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
