<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Additional Information'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div class="-mx-2.5 flex flex-wrap gap-y-5">
            {{-- Phone --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="phone" value="Phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="phone" name="phone" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('phone', isset($student) ? $student->phone : '') }}" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            {{-- Position --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="position" value="Position" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="position" name="position" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('position', isset($student) ? $student->position : '') }}" />
                <x-input-error :messages="$errors->get('position')" class="mt-2" />
            </div>

            {{-- Address --}}
            <div class="w-full px-2.5">
                <x-input-label for="address" value="Address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="address" name="address" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('address', isset($student) ? $student->address : '') }}" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            {{-- City --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="city" value="City" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="city" name="city" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('city', isset($student) ? $student->city : '') }}" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            {{-- Country --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="country" value="Country" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="country" name="country" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('country', isset($student) ? $student->country : '') }}" />
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>

            {{-- Church Affiliation --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="church_affiliation" value="Church Affiliation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="church_affiliation" name="church_affiliation" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('church_affiliation', isset($student) ? $student->church_affiliation : '') }}" />
                <x-input-error :messages="$errors->get('church_affiliation')" class="mt-2" />
            </div>

            {{-- Social Links --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="social_links" value="Social Links" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <x-text-input id="social_links" name="social_links" type="text" placeholder="https://linkedin.com/in/username" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('social_links', isset($student) ? $student->social_links : '') }}" />
                <x-input-error :messages="$errors->get('social_links')" class="mt-2" />
            </div>
        </div>

    </div>
</div>
