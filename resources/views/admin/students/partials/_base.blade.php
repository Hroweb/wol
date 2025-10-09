<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Basic Information'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div class="-mx-2.5 flex flex-wrap gap-y-5">
            {{-- First Name --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="first_name" value="First Name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="first_name" name="first_name" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('first_name', isset($student) ? $student->first_name : '') }}" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            {{-- Last Name --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="last_name" value="Last Name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="last_name" name="last_name" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('last_name', isset($student) ? $student->last_name : '') }}" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            {{-- Email --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="email" value="Email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="email" name="email" type="email" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('email', isset($student) ? $student->email : '') }}" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="password" value="Password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="password" name="password" type="password" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                @if(isset($student))
                    <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                @endif
            </div>

            {{-- Password Confirmation --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="password_confirmation" value="Confirm Password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            {{-- Date of Birth --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="date_of_birth" value="Date of Birth" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth?->format('Y-m-d') : '') }}" />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>
        </div>

    </div>
</div>
