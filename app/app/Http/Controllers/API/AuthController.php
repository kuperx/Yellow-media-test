<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServiceInterface;
use App\DTO\UserDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    const PASSWORD_VALIDATION_RULE = 'required|min:6';

    public function register(Request $request, UserServiceInterface $userService)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'required|unique:users|max:255',
            'password' => self::PASSWORD_VALIDATION_RULE // we can use current_password here but it may depends of requirements
        ]); // we use validation like this because Lumen does not have forms for validations

        $userDTO = new UserDTO(
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('email'),
            $request->input('phone'),
            $request->input('password')
        );

        $user = $userService->create($userDTO);

        if (!$user) {
            return response()->json(['message' => 'Unexpected error, user not created'], 500);
        }

        return response()->json($user, 201);
    }

    public function signIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ]);
    }

    public function passwordRecoverGenerateToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required'
        ]);

        $email = $request->only('email');

        try {
            $response = Password::sendResetLink($email);
        } catch (\Exception $e) {
            // email settings are incorrect
            $response = 0;
        }

        return match ($response) {
            Password::INVALID_USER => response()->json(['message' => 'User with this email not found'], 404),
            Password::RESET_THROTTLED => response()->json(['message' => 'Too many requests, try later'], 429),
            Password::RESET_LINK_SENT => response()->json(['message' => 'Token sent']),
            default => response()->json(['message' => 'Unexpected error, token was not sent'], 500)
        };
    }

    public function passwordRecover(Request $request, UserServiceInterface $userService)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required',
            'password' => self::PASSWORD_VALIDATION_RULE,
        ]);

        $data = $request->only('email', 'password', 'token');

        $response = Password::reset($data, function ($user, $password) use ($userService) {
            $userService->setPassword($user, $password);
        });

        return match ($response) {
            Password::INVALID_USER => response()->json(['message' => 'User with this email not found'], 404),
            Password::INVALID_TOKEN => response()->json(['message' => 'Invalid token'], 401),
            Password::PASSWORD_RESET => response()->json(['message' => 'Password updated']),
            default => response()->json(['message' => 'Unexpected error, password not updated'], 500)
        };
    }
}
