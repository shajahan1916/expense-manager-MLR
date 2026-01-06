<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllActive()
    {
        return User::where('is_deleted', 0)->get();
    }

    public function findByGuid(string $guid)
    {
        return User::where('guid', $guid)
            ->where('is_deleted', 0)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function updateByGuid(string $guid, array $data)
    {
        $user = $this->findByGuid($guid);
        $user->update($data);

        return $user;
    }

    public function softDelete(string $guid)
    {
        $user = $this->findByGuid($guid);
        $user->update(['is_deleted' => 1]);

        return $user;
    }
}
