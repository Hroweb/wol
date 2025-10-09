<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Students') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Students" />

        <div class="space-y-5 sm:space-y-6">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                {{-- Export + Add new --}}
                <x-admin.tables.top
                    :title="'John 10:11'"
                    :slug="'I am the good shepherd. The good shepherd lays down his life for the sheep'"
                    :export="true"
                    :addNew="true"
                    :page="'students'"
                />

                <div class="custom-scrollbar overflow-x-auto">
                    <div
                        class="custom-scrollbar overflow-x-auto"
                        x-data="tableSelect({ items: @js($students->getCollection()->pluck('id')) })"
                    >
                        <table class="w-full table-auto">
                            <thead>
                                @if($students->isNotEmpty())
                                    <x-admin.tables.headers
                                        :columns="[ 'Name', 'Email', 'Position', 'Affiliation', 'Created' ,'Actions']"
                                        :sortable="['name', 'email', 'position', 'church_affiliation', 'created_at']"
                                        :checkBox="true"
                                    />
                                @endif
                            </thead>

                            <tbody class="divide-x divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($students as $s)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                    {{-- Select checkbox --}}
                                    <td class="w-10 px-5 py-4 whitespace-nowrap">
                                        <label class="flex justify-center cursor-pointer select-none">
                                            <input
                                                type="checkbox"
                                                class="sr-only"
                                                @change="toggle({{ $s->id }})"
                                                :checked="isSelected({{ $s->id }})"
                                            >
                                            <span
                                                class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
                                                :class="isSelected({{ $s->id }}) ? 'bg-blue-button bg-blue-border' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                            >
                                                    <span :class="isSelected({{ $s->id }}) ? '' : 'opacity-0'">
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                </span>
                                        </label>
                                    </td>

                                    {{-- Name --}}
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                            {{ \App\Helpers\Helper::getAuthFullName($s) }}
                                        </span>
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                            {{ $s->email }}
                                        </span>
                                    </td>

                                    {{-- Position --}}
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                            {{ $s->localized['position'] ?? $s->position }}
                                        </span>
                                    </td>

                                    {{-- Affiliation --}}
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                            {{ $s->localized['church_affiliation'] ?? $s->church_affiliation }}
                                        </span>
                                    </td>

                                    {{-- Created_At --}}
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                            {{ \App\Helpers\Helper::convertDate($s->created_at) }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <x-admin.tables.actions :id="$s->id" :page="'students'" />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-6 text-center text-sm text-gray-500">
                                        No data found
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($students->hasPages())
                        <x-admin.tables.paginate :paginator="$students" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
