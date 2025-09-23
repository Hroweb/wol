<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teachers') }}
        </h2>
    </x-slot>
    <div class="customers-page admin-page">
        <div x-data="{ pageName: `Teachers`}">
            <x-admin.breadcrumbs page="Teachers" />

            <div class="space-y-5 sm:space-y-6">
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]" x-data="productTable()">
                    {{--export + add new buttons--}}
                    <x-admin.tables.top
                        :title="'John 10:11'"
                        :slug="'I am the good shepherd. The good shepherd lays down his life for the sheep'"
                        :export="true"
                        :addNew="true" />

                    <div class="custom-scrollbar overflow-x-auto">
                        @if($teachers)
                            <div class="custom-scrollbar overflow-x-auto"
                                 x-data="tableSelect({ items: @js($teachers->getCollection()->pluck('id')) })"
                                 @selection-changed="e => console.log('selected ids:', e.detail.selectedItems)">
                                <table class="w-full table-auto">
                                    <thead>
                                    <x-admin.tables.headers
                                        :columns="['Photo', 'Name', 'Email', 'Position', 'Actions']"
                                        :sortable="['name', 'email']"
                                        :checkBox="true"
                                    />
                                    </thead>
                                    <tbody class="divide-x divide-y divide-gray-200 dark:divide-gray-800">
                                    @forelse($teachers as $t)
                                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-900">
                                            <td class="w-14 px-5 py-4 whitespace-nowrap">
                                                <label class="flex justify-center cursor-pointer text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                                    <input
                                                        type="checkbox"
                                                        class="sr-only"
                                                        @change="toggle({{ $t->id }})"
                                                        :checked="isSelected({{ $t->id }})"
                                                    >
                                                    <span :class="isSelected({{ $t->id }}) ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                          class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]">
                                                            <span :class="isSelected({{ $t->id }}) ? '' : 'opacity-0'">
                                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M10 3L4.5 8.5L2 6"
                                                                          stroke="white"
                                                                          stroke-width="1.6666"
                                                                          stroke-linecap="round"
                                                                          stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                </label>
                                            </td>
                                            <td class="w-14 px-5 py-4 whitespace-nowrap">
                                                <img src="{{$t->photo}}" alt="{{$t->localized['first_name']}}">
                                            </td>
                                            <td class="w-14 px-5 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                        {{\App\Helpers\Helper::getAuthFullName($t->localized)}}
                                                    </span>
                                            </td>
                                            <td class="w-14 px-5 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                        {{$t->email}}
                                                    </span>
                                            </td>
                                            <td class="w-14 px-5 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                        {{$t->localized['position']}}
                                                    </span>
                                            </td>
                                            <td class="w-14 px-5 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                        <a href="#">Edit</a> | <a href="#">Delete</a>
                                                    </span>
                                            </td>
                                        </tr>
                                    @empty
                                        No data found
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
