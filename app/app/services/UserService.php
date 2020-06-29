<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function save(string $name): array
    {
        return $this->userRepository->insert(
            [
                'name' => $name,
            ]
        );
    }

    public function getNameById(int $id): ?string
    {
        return $this->userRepository->getNameById($id);
    }
}