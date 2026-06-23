<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Custom provider for the legacy `accounts` table which stores passwords
 * in plaintext (char(41) field). Maps the `email` credential key to the
 * `useremail` column so Filament's standard login form works unchanged.
 */
class PlaintextUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        // Map Filament's 'email' key to the actual column name
        $mapped = [];
        foreach ($credentials as $key => $value) {
            if ($key === 'password') {
                continue;
            }
            $mapped[$key === 'email' ? 'useremail' : $key] = $value;
        }

        return $this->createModel()
            ->newQuery()
            ->where($mapped)
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        // Plaintext comparison — no bcrypt involved
        return $user->getAuthPassword() === $credentials['password'];
    }
}
