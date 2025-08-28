<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

        // @Todo: Implement role assignment logic.

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
