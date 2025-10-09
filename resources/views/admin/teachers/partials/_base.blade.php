<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Basic Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">

        <div class="-mx-2.5 flex flex-wrap gap-y-5">
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="photo" value="Photo" class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-400" />

                {{-- Photo Upload Area --}}
                <div class="relative" x-data="photoUpload()">
                    {{-- Current/Preview Photo - Clickable --}}
                    <label for="photo" class="block w-full cursor-pointer">
                        <div class="bg-white dark:bg-gray-800 rounded-lg border-1 border-dashed border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors p-6">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-3">
                                    {{-- Show preview if new file selected, otherwise show current photo --}}
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" alt="Preview" class="h-20 w-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" />
                                    </template>
                                    <template x-if="!previewUrl">
                                        @if(isset($teacher) && $teacher->photo)
                                            <img src="{{ asset('storage/'. $teacher->photo) }}" alt="Current photo" class="h-20 w-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" />
                                        @else
                                            <div class="h-20 w-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </template>

                                    {{-- Status indicator --}}
                                    <div x-show="previewUrl" class="absolute -bottom-0 -right-0 h-6 w-6 rounded-full border-2 border-white flex items-center justify-center"
                                         :class="previewUrl ? 'bg-blue-button' : 'bg-blue-button'">
                                        <svg x-show="previewUrl" class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg x-show="!previewUrl" class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Dynamic text based on state --}}
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="previewUrl ? 'New Photo Selected' : '{{ isset($teacher) && $teacher->photo ? "Current Photo" : "Upload Photo" }}'"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="previewUrl ? 'Ready to upload' : 'Click to {{ isset($teacher) && $teacher->photo ? "change photo" : "select image" }}'"></span>
                            </div>
                        </div>
                    </label>

                    {{-- File Input --}}
                    <input id="photo" name="photo" type="file" class="hidden" accept="image/*" @change="handleFileSelect($event)" />
                </div>


                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
            </div>
            {{--<div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="photo" value="Photo" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" />
                <input id="photo" name="photo" type="file" class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" accept="image/*" />
                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                @if(isset($teacher) && $teacher->photo)
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ asset('storage/'. $teacher->photo) }}" alt="Current photo" class="h-12 w-12 rounded-full object-cover border border-gray-200" />
                        <span class="text-xs text-gray-500">Current photo</span>
                    </div>
                @endif
            </div>--}}

            {{-- Email --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" placeholder="info@gmail.com" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('email', isset($teacher) ? $teacher->email : '') }}" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Instagram --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="social_ig" value="Instagram" />
                <x-text-input id="social_ig" name="social_ig" type="text" placeholder="https://instagram.com/user/username" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('social_ig', isset($teacher) ? $teacher->social_ig : '') }}" />
                <x-input-error :messages="$errors->get('social_ig')" class="mt-2" />
            </div>

            {{-- YouTube --}}
            <div class="w-full px-2.5 xl:w-1/2">
                <x-input-label for="social_youtube" value="YouTube" />
                <x-text-input id="social_youtube" name="social_youtube" type="text" placeholder="https://youtube.com/user/username" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('social_youtube', isset($teacher) ? $teacher->social_youtube : '') }}" />
                <x-input-error :messages="$errors->get('social_youtube')" class="mt-2" />
            </div>

            {{-- Featured --}}
            <div class="w-full px-2.5 xl:w-1/2" x-data="{ featured: {{ old('is_featured', isset($teacher) ? (int)$teacher->is_featured : 0) ? 'true' : 'false' }} }">

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
                        :class="featured ? 'bg-blue-button bg-blue-border' : 'border-gray-300 dark:border-gray-700'"
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
