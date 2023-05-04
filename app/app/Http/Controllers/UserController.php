<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DTO\UserDTO;
use App\Services\UserServiceInterface;

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

        return response()->json($user);
    }
}
