<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterAction
{
    use AsAction;

    public function handle(array $data): User
    {
        $user = User::create([
            'psn_id'   => $data['psn_id'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);

        event(new Registered($user));

        return $user;
    }
}
