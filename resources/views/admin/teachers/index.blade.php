<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teachers') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Teachers" />

        <div class="space-y-5 sm:space-y-6">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                {{-- Export + Add new --}}
                <x-admin.tables.top
                    :title="'John 10:11'"
                    :slug="'I am the good shepherd. The good shepherd lays down his life for the sheep'"
                    :export="true"
                    :addNew="true"
                    :page="'teachers'"
                />

                <div class="custom-scrollbar overflow-x-auto">
                    @if($teachers)
                        <div
                            class="custom-scrollbar overflow-x-auto"
                            x-data="tableSelect({ items: @js($teachers->getCollection()->pluck('id')) })"
                        >
                            <table class="w-full table-auto">
                                <thead>
                                    <x-admin.tables.headers
                                        :columns="['Photo', 'Name', 'Email', 'Position', 'Church Name', 'Created' ,'Actions']"
                                        :sortable="['name', 'email', 'position', 'created_at']"
                                        :checkBox="true"
                                    />
                                </thead>

                                <tbody class="divide-x divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($teachers as $t)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                        {{-- Select checkbox --}}
                                        <td class="w-10 px-5 py-4 whitespace-nowrap">
                                            <label class="flex justify-center cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    class="sr-only"
                                                    @change="toggle({{ $t->id }})"
                                                    :checked="isSelected({{ $t->id }})"
                                                >
                                                <span
                                                    class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
                                                    :class="isSelected({{ $t->id }}) ? 'bg-blue-button bg-blue-border' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                >
                                                        <span :class="isSelected({{ $t->id }}) ? '' : 'opacity-0'">
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </span>
                                                    </span>
                                            </label>
                                        </td>

                                        {{-- Photo --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($t->photo && file_exists(public_path('storage/' . $t->photo)))
                                                    <img
                                                        src="{{ asset('storage/'.$t->photo) }}"
                                                        alt="{{ $t->localized['first_name'] ?? $t->first_name }}"
                                                        class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                                    >
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Name --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ \App\Helpers\Helper::getAuthFullName($t->localized) }}
                                            </span>
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $t->email }}
                                            </span>
                                        </td>

                                        {{-- Position --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $t->localized['position'] ?? $t->position }}
                                            </span>
                                        </td>

                                        {{-- Church Name --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $t->localized['church_name'] ?? $t->church_name }}
                                            </span>
                                        </td>

                                        {{-- Created_At --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ \App\Helpers\Helper::convertDate($t->created_at) }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <x-admin.tables.actions :id="$t->id" :page="'teachers'" />
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
                    @endif
                    @if($teachers->hasPages())
                        <x-admin.tables.paginate :paginator="$teachers" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
