<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Pages" />

        <div class="space-y-5 sm:space-y-6">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                {{-- Export + Add new --}}
                <x-admin.tables.top
                    :title="'Content Management'"
                    :slug="'Manage your website pages'"
                    :export="false"
                    :addNew="true"
                    :page="'pages'"
                />

                <div class="custom-scrollbar overflow-x-auto">
                    @if($pages->isNotEmpty())
                        <div
                            class="custom-scrollbar overflow-x-auto"
                            x-data="tableSelect({ items: @js($pages->getCollection()->pluck('id')) })"
                        >
                            <table class="w-full table-auto">
                                <thead>
                                <x-admin.tables.headers
                                    :columns="['Title', 'Slug', 'Template', 'Status', 'Order', 'Actions']"
                                    :sortable="['title', 'slug', 'order', 'created_at']"
                                    :checkBox="true"
                                />
                                </thead>

                                <tbody class="divide-x divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($pages as $page)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                        {{-- Select checkbox --}}
                                        <td class="w-10 px-5 py-4 whitespace-nowrap">
                                            <label class="flex justify-center cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    class="sr-only"
                                                    @change="toggle({{ $page->id }})"
                                                    :checked="isSelected({{ $page->id }})"
                                                >
                                                <span
                                                    class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
                                                    :class="isSelected({{ $page->id }}) ? 'bg-blue-button bg-blue-border' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                >
                                                    <span :class="isSelected({{ $page->id }}) ? '' : 'opacity-0'">
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </label>
                                        </td>

                                        {{-- Title --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="{{ \App\Helpers\Helper::translationClass($page->localized['title'] ?? '') }}">
                                                {{ $page->localized['title'] ?? 'No title' }}
                                            </span>
                                        </td>

                                        {{-- Slug --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $page->slug }}
                                            </span>
                                        </td>

                                        {{-- Template --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $page->template }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400">
                                                @if($page->is_published) Published @else Draft @endif
                                            </span>
                                        </td>

                                        {{-- Order --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $page->order }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <x-admin.tables.actions :id="$page->id" :page="'pages'" />
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-6 text-center text-sm text-gray-500">
                                            No data found
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if($pages->hasPages())
                        <x-admin.tables.paginate :paginator="$pages" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

