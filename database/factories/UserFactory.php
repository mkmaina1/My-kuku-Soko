<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $roles = ['farmer', 'supplier', 'agent', 'veterinary'];
        $role = $this->faker->randomElement($roles);

        // Profile image URLs
        $profileImages = [
            'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop',
            'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop',
            'https://via.placeholder.com/150/4CAF50/FFFFFF?text=' . strtoupper(substr($this->faker->firstName(), 0, 1)),
            'https://via.placeholder.com/150/2196F3/FFFFFF?text=' . strtoupper(substr($this->faker->firstName(), 0, 1)),
        ];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => $role,
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'profile_image' => $this->faker->randomElement($profileImages),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function farmer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'farmer',
        ]);
    }

    public function supplier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'supplier',
        ]);
    }

    public function agent(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'agent',
        ]);
    }

    public function veterinary(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'veterinary',
            'name' => 'Dr. ' . $this->faker->name(),
        ]);
    }
}
