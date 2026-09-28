<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\VerifyEmailCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailCodeRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(VerifyEmailCodeRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('discover');
        }

        $verified = VerifyEmailCodeAction::run($request->user(), $request->code);

        if (! $verified) {
            return redirect()->back()->withErrors(['code' => 'This code is invalid or has expired.']);
        }

        return redirect()->route('discover');
    }
}
