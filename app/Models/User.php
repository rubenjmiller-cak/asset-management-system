<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles, Notifiable;

    protected $table = 'accounts';
    protected $primaryKey = 'AccountID';

    protected $fillable = [
        'username',
        'useremail',
        'password',
        'FirstName',
        'LastName',
        'userlevel',
        'Status',
    ];

    protected $hidden = ['password'];

    // No password hashing — accounts table uses plaintext
    protected function casts(): array
    {
        return [];
    }

    // Filament reads $user->name
    public function getNameAttribute(): string
    {
        return trim("{$this->FirstName} {$this->LastName}") ?: $this->username;
    }

    // Filament reads $user->email
    public function getEmailAttribute(): ?string
    {
        return $this->useremail;
    }

    public function getAuthIdentifierName(): string
    {
        return 'AccountID';
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password;
    }

    // -1 = PHPMaker admin level; 0+ = regular users; Status 1 = active
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->Status == 1;
    }
}
