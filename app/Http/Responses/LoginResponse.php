<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $request->session()->forget('url.intended');
        $target = ($user && ($user->role === 'admin'))
            ? route('admin.dashboard')
            : route('home');

        return redirect()->intended($target);
    }
}
