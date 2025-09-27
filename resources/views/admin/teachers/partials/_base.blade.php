<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Basic Fields
        </h3>
    </div>
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div class="-mx-2.5 flex flex-wrap gap-y-5">
            {{-- Photo --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="photo" value="Photo" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <input id="photo" name="photo" type="file" class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" accept="image/*" />
                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
            </div>

            {{-- Email --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" placeholder="info@gmail.com" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('email') }}" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Instagram --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="social_ig" value="Instagram" />
                <x-text-input id="social_ig" name="social_ig" type="text" placeholder="https://instagram.com/user/username" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('social_ig') }}" />
                <x-input-error :messages="$errors->get('social_ig')" class="mt-2" />
            </div>

            {{-- YouTube --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="social_youtube" value="YouTube" />
                <x-text-input id="social_youtube" name="social_youtube" type="text" placeholder="https://youtube.com/user/username" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('social_youtube') }}" />
                <x-input-error :messages="$errors->get('social_youtube')" class="mt-2" />
            </div>

            {{-- Featured --}}
            <div class="w-full px-2.5 xl:w-1/2" x-data="{ featured: {{ old('is_featured') ? 'true' : 'false' }} }">

                <label class="mt-2 inline-flex items-center gap-2 cursor-pointer select-none" for="is_featured">
                    <input type="hidden" name="is_featured" value="0">
                    <input
                        id="is_featured"
                        name="is_featured"
                        type="checkbox"
                        value="1"
                        class="sr-only"
                        x-model="featured"
                    >
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
                        :class="featured ? 'border-brand-500 bg-brand-500' : 'border-gray-300 dark:border-gray-700'"
                        aria-hidden="true"
                    >
                        <span :class="featured ? 'opacity-100' : 'opacity-0'" class="transition-opacity">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </span>
                    <span class="text-sm text-gray-700 dark:text-gray-400">Is Featured</span>
                </label>
                <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
            </div>

        </div>

    </div>
</div>
