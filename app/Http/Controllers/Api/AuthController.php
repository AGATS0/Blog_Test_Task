<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterAction $action):JsonResponse
    {
        $result = $action->execute(
            $request->name,
            $request->email,
            $request->password
        );

        return response()->json([
            'user'=> $result['user'],
            'accessToken' => $result['accessToken'],
        ],201);
    }

    public function login(LoginRequest $request,LoginAction $action): JsonResponse
    {

    $result = $action->execute(
        $request->email,
        $request->password,
    );

    return response()->json([
        'user' => $result['user'],
        'accessToken' => $result['accessToken'],
    ]);

    }
}
