<?php

namespace App\Modules\User\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public const ROLE_DISPATCHER = 'dispatcher';
    public const ROLE_MASTER = 'master';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isDispatcher(): bool
    {
        return $this->role === self::ROLE_DISPATCHER;
    }

    public function isMaster(): bool
    {
        return $this->role === self::ROLE_MASTER;
    }

    /** Имя без префикса роли («Мастер …» / «Диспетчер …») */
    public function displayName(): string
    {
        $clean = preg_replace('/^(Мастер|Диспетчер)\s+/u', '', $this->name);

        return $clean !== '' ? $clean : $this->name;
    }

    /** Две буквы: первая буква имени + фамилии (SAP / Material pattern) */
    public function initials(): string
    {
        $words = preg_split('/\s+/u', trim($this->displayName()));

        if (count($words) >= 2) {
            return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }

        return mb_strtoupper(mb_substr($this->displayName(), 0, 2));
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_DISPATCHER => 'Диспетчер',
            self::ROLE_MASTER => 'Мастер',
            default => 'Пользователь',
        };
    }
}
