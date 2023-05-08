<?php

namespace App\Services;

use App\DTO\CompanyDTO;
use App\Models\User;
use App\Models\Company;

interface CompanyServiceInterface
{
    public function create(User $user, CompanyDTO $company): ?Company;
}
