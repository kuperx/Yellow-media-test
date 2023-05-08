<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\DTO\CompanyDTO;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function __construct(
        private Company $company
    ) {}

    public function createCompany(): Company
    {
        return $this->company->newInstance();
    }

    public function createCompanyWithAttributes(array $attributes): Company
    {
        return $this->createCompany()->fill($attributes);
    }

    public function createCompanyByDTO(CompanyDTO $companyDTO): Company
    {
        return $this->createCompanyWithAttributes([
            'title'       => $companyDTO->getTitle(),
            'phone'       => $companyDTO->getPhone(),
            'description' => $companyDTO->getDescription()
        ]);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->word,
            'phone' => $this->faker->unique()->phoneNumber,
            'description' => $this->faker->text,
        ];
    }
}
