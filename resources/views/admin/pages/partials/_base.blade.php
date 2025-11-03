<div class="mb-10 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <x-admin.locales :group="'Basic Fields'" />
    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
        <div class="space-y-5">
            <div>
                <x-input-label for="slug" value="Slug (URL)" />
                <x-text-input id="slug" name="slug" type="text" placeholder="about-us" @input="$dispatch('slug-manually-edited')" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('slug', isset($page) ? $page->slug : '') }}" />
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Slug is auto-generated from the English title. You can edit it manually if needed.</p>
                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="template" value="Template" />
                <select id="template" name="template" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="default" {{ old('template', isset($page) ? $page->template : 'default') == 'default' ? 'selected' : '' }}>Default</option>
                    <option value="home" {{ old('template', isset($page) ? $page->template : '') == 'home' ? 'selected' : '' }}>Home</option>
                    <option value="contact" {{ old('template', isset($page) ? $page->template : '') == 'contact' ? 'selected' : '' }}>Contact</option>
                </select>
                <x-input-error :messages="$errors->get('template')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="order" value="Order" />
                <x-text-input id="order" name="order" type="number" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ old('order', isset($page) ? $page->order : 0) }}" />
                <x-input-error :messages="$errors->get('order')" class="mt-2" />
            </div>

            <div class="flex items-center" x-data="{ published: {{ old('is_published', isset($page) ? $page->is_published : false) ? 'true' : 'false' }} }">
                <x-admin.checkbox :name="'is_published'" :model="'published'" :text="'Is Published'" />
            </div>
        </div>
    </div>
</div>

