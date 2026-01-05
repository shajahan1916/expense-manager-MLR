<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthUser implements JWTSubject
{
    protected string $guid;
    protected string $role;

    public function __construct(string $guid, string $role)
    {
        $this->guid = $guid;
        $this->role = $role;
    }

    public function getJWTIdentifier()
    {
        return $this->guid;
    }

    public function getJWTCustomClaims()
    {
        return [
            'guid' => $this->guid,
            'role' => $this->role,
        ];
    }
}
