<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Courses" />

        <div class="space-y-5 sm:space-y-6">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                {{-- Export + Add new --}}
                <x-admin.tables.top
                    :title="'John 10:11'"
                    :slug="'I am the good shepherd. The good shepherd lays down his life for the sheep'"
                    :export="true"
                    :addNew="true"
                />

                <div class="custom-scrollbar overflow-x-auto">
                    @if($courses->isNotEmpty())
                        <div
                            class="custom-scrollbar overflow-x-auto"
                            x-data="tableSelect({ items: @js($courses->getCollection()->pluck('id')) })"
                        >
                            <table class="w-full table-auto">
                                <thead>
                                <x-admin.tables.headers
                                    :columns="['Title', 'Description', 'Academic Year', 'Start Date', 'End Date', 'Actions']"
                                    :sortable="['title', 'description', 'start_date', 'end_date', 'created_at']"
                                    :checkBox="true"
                                />
                                </thead>

                                <tbody class="divide-x divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($courses as $c)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                        {{-- Select checkbox --}}
                                        <td class="w-10 px-5 py-4 whitespace-nowrap">
                                            <label class="flex justify-center cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    class="sr-only"
                                                    @change="toggle({{ $c->id }})"
                                                    :checked="isSelected({{ $c->id }})"
                                                >
                                                <span
                                                    class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
                                                    :class="isSelected({{ $c->id }}) ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                >
                                                        <span :class="isSelected({{ $c->id }}) ? '' : 'opacity-0'">
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </span>
                                                    </span>
                                            </label>
                                        </td>

                                        {{-- Title --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{$c->localized['title']}}
                                            </span>
                                        </td>

                                        {{-- Description --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{$c->localized['description']}}
                                            </span>
                                        </td>

                                        {{-- Email --}}{{--
                                        <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-400">

                                                </span>
                                        </td>--}}

                                        {{-- Academic Year --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    {{$c->academic_year}}
                                                </span>
                                        </td>

                                        {{-- Start Date --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    {{\App\Helpers\Helper::convertDate($c->start_date)}}
                                                </span>
                                        </td>

                                        {{-- End Date --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    {{\App\Helpers\Helper::convertDate($c->end_date)}}
                                                </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                <a href="#" class="text-sm font-medium text-indigo-600 hover:underline">Edit</a>
                                            </span>
                                            <span class="px-1 text-gray-400">|</span>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                <a href="#" class="text-sm font-medium text-rose-600 hover:underline">Delete</a>
                                            </span>
                                        </td>
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
                    @if($courses->hasPages())
                        <x-admin.tables.paginate :paginator="$courses" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
