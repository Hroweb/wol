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
                    :page="'courses'"
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
                                    :columns="['Title', 'Academic Year', 'Students', 'Start Date', 'End Date', 'Actions']"
                                    :sortable="['title', 'start_date', 'end_date', 'created_at']"
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
                                                    :class="isSelected({{ $c->id }}) ? 'bg-blue-button bg-blue-border' : 'bg-transparent border-gray-300 dark:border-gray-700'"
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
                                            <span class="{{ \App\Helpers\Helper::translationClass($c->localized['title']) }}">
                                                {{$c->localized['title']}}
                                            </span>
                                        </td>

                                        {{-- Description --}}
                                        {{--<td class="w-[23%] px-5 py-4 whitespace-nowrap">
                                            <span class="{{ \App\Helpers\Helper::translationClass($c->localized['description']) }}">
                                                {{$c->localized['description'] ?? $c->description}}
                                            </span>
                                        </td>--}}

                                        {{-- Academic Year --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="{{ \App\Helpers\Helper::translationClass($c->academic_year) }}">
                                                    {{$c->academic_year}}
                                                </span>
                                        </td>

                                        {{-- Students Count --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium dark:text-gray-400">
                                                    {{ $c->users->count() ?? 0 }} {{Str::plural('student', $c->users->count())}}
                                                </span>
                                            </div>
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
                                        <x-admin.tables.actions :id="$c->id" :page="'courses'" />
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
