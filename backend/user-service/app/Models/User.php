<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    /**
     * Primary key settings
     */
    protected $primaryKey = 'user_id';
    protected $keyType = 'int';
    public $incrementing = true;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'guid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'is_deleted'
    ];

    /**
     * Hidden attributes (never expose in API)
     */
    protected $hidden = [
        'password',
        'user_id',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Automatically generate GUID on create
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->guid)) {
                $user->guid = (string) Str::uuid();
            }
        });
    }
}
