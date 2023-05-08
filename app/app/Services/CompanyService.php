<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\DTO\CompanyDTO;

class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        private Company $company
    ) {}

    public function create(User $user, CompanyDTO $company): ?Company
    {
        $this->company->fill($company->toArray());

        if (!$user->companies()->save($this->company)) {
            return null;
        }

        return $this->company;
    }
}
