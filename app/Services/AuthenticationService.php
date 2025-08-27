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

        $user->token = $this->createAccessToken($user);

        return $user;
    }

    public function createAccessToken(User $user): string
    {
        return $user->createToken('access_token')->plainTextToken;
    }

    public function logout(bool $revokeAllTokens = false): string
    {
        $user = Auth::user();

        if ($revokeAllTokens) {
            
            $user->tokens()->delete();

            return 'Log out from all devices successful.';
        }
        
        $user->currentAccessToken()->delete();

        return 'Log out successful.';
    }
}
