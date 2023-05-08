<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CompanyServiceInterface;
use App\DTO\CompanyDTO;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function getCompanies(Request $request)
    {
        $user = Auth::user();
        $companies = $user->getCompanies();

        return response()->json($companies);
    }

    public function createCompany(Request $request, CompanyServiceInterface $companyService)
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

        $user = Auth::user();
        $company = $companyService->create($user, $companyDTO);

        if (!$company) {
            return response()->json(['message' => 'Unexpected error, company not created'], 503);
        }

        return response()->json($company, 201);
    }
}
