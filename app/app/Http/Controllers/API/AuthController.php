<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServiceInterface;
use App\DTO\UserDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

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
            return response()->json([], 500);
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
            return response()->json(['status' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
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

        $response = Password::sendResetLink($request->only('email'), function ($user, $token) {
            // @todo make notification by email
            echo $token;
        });

        if ($response == Password::INVALID_USER) {
            return response()->json(['status' => 'error'], 404);
        }

        if ($response == Password::RESET_THROTTLED) {
            return response()->json(['status' => 'error'], 429);
        }

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['status' => 'success'])
            : response()->json(['status' => 'error'], 400);
    }

    public function passwordRecover(Request $request, UserServiceInterface $userService)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required',
            'password' => self::PASSWORD_VALIDATION_RULE,
        ]);

        $response = Password::reset($request->only('email', 'password', 'token'),
            function ($user, $password) use ($userService) {
                $userService->updatePassword($user, $password);
            }
        );

        if ($response == Password::INVALID_USER) {
            return response()->json(['status' => 'error'], 404);
        }

        if ($response == Password::INVALID_TOKEN) {
            return response()->json(['status' => 'error'], 401);
        }

        return $response == Password::PASSWORD_RESET
            ? response()->json(['status' => 'success'])
            : response()->json(['status' => 'error'], 400);
    }
}
