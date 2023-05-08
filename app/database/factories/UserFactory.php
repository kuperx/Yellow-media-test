<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\DTO\UserDTO;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    public function __construct(
        private User $user
    ) {}

    public function createUser(): User
    {
        return $this->user->newInstance();
    }

    public function createUserWithAttributes(array $attributes): User
    {
        return $this->createUser()->fill($attributes);
    }

    public function createUserByDTO(UserDTO $userDTO): User
    {
        $user = $this->createUserWithAttributes([
            'first_name' => $userDTO->getFirstName(),
            'last_name'  => $userDTO->getLastName(),
            'email'      => $userDTO->getEmail(),
            'phone'      => $userDTO->getPhone()
        ]);

        $user->setPassword($userDTO->getPassword());

        return $user;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => Hash::make($this->faker->password)
        ];
    }
}
