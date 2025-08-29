<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(array $data): User
    {
        $user = User::create($data);

        $user->assignRole(RoleName::CUSTOMER->value);

        return $user;
    }

    public function createAccessToken(User $user): string
    {
        return $user->createToken('access_token')->plainTextToken;
    }

    public function logout(bool $revokeAllTokens = false): void
    {
        $user = Auth::user();

        if ($revokeAllTokens) {

            $user->tokens()->delete();

        } else {

            $user->currentAccessToken()->delete();

        }
    }
}
