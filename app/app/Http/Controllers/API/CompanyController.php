<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserServiceInterface;
use App\DTO\CompanyDTO;

class CompanyController extends Controller
{
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
            return response()->json(['message' => 'Unexpected error, company not created'], 500);
        }

        return response()->json($company, 201);
    }
}
