<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;

class UserService
{
    private const USERS_CACHE_KEY = 'users_all';

    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Cached read (best practice)
     */
    public function getAllUsers()
    {
        return Cache::remember(
            self::USERS_CACHE_KEY,
            now()->addMinutes(5),
            fn () => $this->userRepository->getAllActive()
        );
    }

    /**
     * Simple lookup (no cache)
     */
    public function getUserByGuid(string $guid)
    {
        return $this->userRepository->findByGuid($guid);
    }

    /**
     * Writes → clear cache
     */
    public function createUser(array $data)
    {
        $user = $this->userRepository->create($data);
        Cache::forget(self::USERS_CACHE_KEY);

        return $user;
    }

    public function updateUser(string $guid, array $data)
    {
        $user = $this->userRepository->updateByGuid($guid, $data);
        Cache::forget(self::USERS_CACHE_KEY);

        return $user;
    }

    public function deleteUser(string $guid)
    {
        $user = $this->userRepository->softDelete($guid);
        Cache::forget(self::USERS_CACHE_KEY);

        return $user;
    }
}
