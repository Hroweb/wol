<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
    {
        $request->session()->forget('url.intended');
        // After registration, go to home page
        return redirect()->intended(route('home'));
    }
}
