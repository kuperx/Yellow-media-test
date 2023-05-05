<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\DTO\UserDTO;
use App\DTO\CompanyDTO;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request, UserServiceInterface $userService)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'required|unique:users|max:255',
            'password' => 'required|min:6' // we can use current_password here but it may depends of requirements
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
            return response()->json(['status' => 'error'], 401);
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

    public function getCompanies(Request $request, User $user)
    {
        $companies = $user->companies;

        return response()->json($companies);
    }

    public function createCompany(Request $request, User $user, UserServiceInterface $userService)
    {
        $this->validate($request, [
            'title' => 'required|unique:companies|max:255',
            'phone' => 'required|max:255',
            'description' => 'required'
        ]);

        $companyDTO = new CompanyDTO(
            $request->input('title'),
            $request->input('phone'),
            $request->input('description')
        );

        $company = $userService->createCompany($user, $companyDTO);

        if (!$company) {
            return response()->json([], 500);
        }

        return response()->json($company, 201);
    }
}
