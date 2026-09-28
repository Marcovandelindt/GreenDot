<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Lorisleiva\Actions\Concerns\AsAction;

class VerifyEmailCodeAction
{
    use AsAction;

    public function handle(User $user, string $code): bool
    {
        if (
            $user->email_verification_code === null ||
            $user->email_verification_expires_at === null ||
            $user->email_verification_expires_at->isPast()
        ) {
            return false;
        }

        if (! hash_equals($user->email_verification_code, hash('sha256', $code))) {
            return false;
        }

        $user->update([
            'email_verification_code'       => null,
            'email_verification_expires_at' => null,
        ]);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return true;
    }
}
