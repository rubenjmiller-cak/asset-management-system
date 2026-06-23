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
        $login = $credentials['email'] ?? null;
        if (!$login) {
            return null;
        }

        // Accept username or email address
        return $this->createModel()
            ->newQuery()
            ->where(function ($q) use ($login) {
                $q->where('username', $login)
                  ->orWhere('useremail', $login);
            })
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        // Plaintext comparison — no bcrypt involved
        return $user->getAuthPassword() === $credentials['password'];
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // Never rehash — passwords stay plaintext until a schema migration upgrades them
    }
}
