<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\DTO\CompanyDTO;
use Database\Factories\CompanyFactory;

class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        private CompanyFactory $companyFactory
    ) {}

    public function create(User $user, CompanyDTO $companyDTO): ?Company
    {
        $company = $this->companyFactory->createCompanyByDTO($companyDTO);

        if (!$user->setCompany($company)) {
            return null;
        }

        return $company;
    }
}
