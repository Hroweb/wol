<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex w-full min-h-screen">
        <!-- Left visual panel -->
        <div class="hidden md:block w-1/2 bg-[#101828]"></div>

        <!-- Right form panel -->
        <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-6">
            <form method="POST" action="{{ route('login') }}" class="w-80 md:w-96 flex flex-col items-center">
                @csrf

                <h2 class="text-4xl text-gray-900 font-medium">Sign in</h2>
                <p class="text-sm text-gray-500/90 mt-3">Welcome back! Please sign in to continue</p>

                {{-- (Optional) Social login button placeholder, keep your visual --}}
                <button type="button" class="w-full mt-8 bg-gray-500/10 flex items-center justify-center h-12 rounded-full">
                    <img src="https://raw.githubusercontent.com/prebuiltui/prebuiltui/main/assets/login/googleLogo.svg" alt="googleLogo">
                </button>

                <div class="flex items-center gap-4 w-full my-5">
                    <div class="w-full h-px bg-gray-300/90"></div>
                    <p class="w-full text-nowrap text-sm text-gray-500/90">or sign in with email</p>
                    <div class="w-full h-px bg-gray-300/90"></div>
                </div>

                <!-- Email -->
                <div class="w-full">
                    <x-input-label for="email" :value="__('Email')" class="sr-only" />
                    <div class="flex items-center w-full bg-transparent border border-gray-300/60 h-12 rounded-full overflow-hidden pl-6 gap-2">
                        <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 .55.571 0H15.43l.57.55v9.9l-.571.55H.57L0 10.45zm1.143 1.138V9.9h13.714V1.69l-6.503 4.8h-.697zM13.749 1.1H2.25L8 5.356z" fill="#6B7280"/>
                        </svg>

                        <x-text-input
                            id="email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Email"
                            class="bg-transparent text-gray-700 placeholder-gray-500/80 outline-none text-sm w-full h-full border-0 focus:ring-0"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="w-full mt-6">
                    <x-input-label for="password" :value="__('Password')" class="sr-only" />
                    <div class="flex items-center w-full bg-transparent border border-gray-300/60 h-12 rounded-full overflow-hidden pl-6 gap-2">
                        <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 8.5c0-.938-.729-1.7-1.625-1.7h-.812V4.25C10.563 1.907 8.74 0 6.5 0S2.438 1.907 2.438 4.25V6.8h-.813C.729 6.8 0 7.562 0 8.5v6.8c0 .938.729 1.7 1.625 1.7h9.75c.896 0 1.625-.762 1.625-1.7zM4.063 4.25c0-1.406 1.093-2.55 2.437-2.55s2.438 1.144 2.438 2.55V6.8H4.061z" fill="#6B7280"/>
                        </svg>

                        <x-text-input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                            class="bg-transparent text-gray-700 placeholder-gray-500/80 outline-none text-sm w-full h-full border-0 focus:ring-0"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember + Forgot -->
                <div class="w-full flex items-center justify-between mt-8 text-gray-500/80">
                    <label for="remember_me" class="inline-flex items-center gap-2 text-sm">
                        <input id="remember_me" type="checkbox" name="remember" class="h-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span>{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <x-primary-button class="mt-8 w-full h-11 rounded-full text-white bg-[#101828] hover:opacity-90 transition-opacity justify-center">
                    {{ __('Log in') }}
                </x-primary-button>

                <!-- Sign up link -->
                @if (Route::has('register'))
                    <p class="text-gray-500/90 text-sm mt-4">
                        {{ __("Don’t have an account?") }}
                        <a class="text-[#101828] hover:underline" href="{{ route('register') }}">{{ __('Sign up') }}</a>
                    </p>
                @endif
            </form>
        </div>
    </div>
</x-auth-layout>
