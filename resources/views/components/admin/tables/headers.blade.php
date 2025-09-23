@props(['checkBox' => false, 'columns' => false, 'sortable' => false])

<tr class="border-b border-gray-200 dark:divide-gray-800 dark:border-gray-800">
    @if($checkBox)
        <th class="w-4 px-5 py-4 text-left checkBoxTbl">
            <label class="flex justify-center cursor-pointer text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                <input
                    type="checkbox"
                    class="sr-only select-all-checkbox"
                    @click="toggleAll()"
                    :checked="isAllSelected()"
                >
                <span
                    :class="isAllSelected() ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                    class="w-5 h-5 flex items-center justify-center rounded-sm border-[1.25px]"
                >
                    <span :class="isAllSelected() ? '' : 'opacity-0'">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </span>
            </label>
        </th>
    @endif
    @forelse($columns as $col)
        @php
            // Use lowercase keys for query param
            $colKey = strtolower($col) === 'created' ? 'created_at' : strtolower($col);
            $isSortable = in_array($colKey, $sortable);
            $isCurrent = request('sort') === $colKey;
            $dir = $isCurrent && request('dir') === 'asc' ? 'desc' : 'asc';
            $url = request()->fullUrlWithQuery(['sort' => $colKey, 'dir' => $dir]);
        @endphp
        <th class="cursor-pointer px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400" @click="sortBy('name')">
            @if ($isSortable)
                <a href="{{ $url }}" class="inline-flex items-center gap-1">
                    <span>{{ $col }}</span>
                    <span class="flex flex-col leading-none">
                    {{-- up arrow --}}
                    <svg class="{{ $isCurrent && request('dir') === 'asc' ? 'text-gray-600 dark:text-gray-300' : 'text-gray-300 dark:text-gray-500/50' }}"
                         width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.41.585a.5.5 0 0 0-.82 0L1.05 4.213A.5.5 0 0 0 1.46 5h5.08a.5.5 0 0 0 .41-.787L4.41.585Z" fill="currentColor"/>
                    </svg>
                    {{-- down arrow --}}
                    <svg class="{{ $isCurrent && request('dir') === 'desc' ? 'text-gray-600 dark:text-gray-300' : 'text-gray-300 dark:text-gray-500/50' }}"
                         width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.41 4.415a.5.5 0 0 1-.82 0L1.05.787A.5.5 0 0 1 1.46 0h5.08a.5.5 0 0 1 .41.787L4.41 4.415Z" fill="currentColor"/>
                    </svg>
                </span>
                </a>
            @else
                <span>{{ $col }}</span>
            @endif
        </th>
        {{--<th class="px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
            {{ $col }}
        </th>--}}
    @empty
    @endforelse
    {{--<th class="cursor-pointer px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400" @click="sortBy('name')">
        <div class="flex items-center gap-3">
            <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                Products
            </p>
            <span class="flex flex-col gap-0.5">
                <svg :class="sort.key === 'name' &amp;&amp; sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-400/50'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-500 dark:text-gray-400">
                  <path d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z" fill="currentColor"></path>
                </svg>

                <svg :class="sort.key === 'name' &amp;&amp; !sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-400/50'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300 dark:text-gray-400/50">
                  <path d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z" fill="currentColor"></path>
                </svg>
              </span>
        </div>
    </th>
    <th class="cursor-pointer px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400" @click="sortBy('category')">
        <div class="flex items-center gap-3">
            <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                Category
            </p>
            <span class="flex flex-col gap-0.5">
                <svg :class="sort.key === 'category' &amp;&amp; sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-400/50'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300 dark:text-gray-400/50">
                  <path d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z" fill="currentColor"></path>
                </svg>

                <svg :class="sort.key === 'category' &amp;&amp; !sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-400/50'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300 dark:text-gray-400/50">
                  <path d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z" fill="currentColor"></path>
                </svg>
              </span>
        </div>
    </th>
    <th class="cursor-pointer px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400" @click="sortBy('brand')">
        <div class="flex items-center gap-3">
            <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                Brand
            </p>
            <span class="flex flex-col gap-0.5">
                <svg :class="sort.key === 'brand' &amp;&amp; sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-400/50'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300 dark:text-gray-400/50">
                  <path d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z" fill="currentColor"></path>
                </svg>

                <svg :class="sort.key === 'brand' &amp;&amp; !sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300 dark:text-gray-400/50'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300 dark:text-gray-400/50">
                  <path d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z" fill="currentColor"></path>
                </svg>
              </span>
        </div>
    </th>
    <th class="cursor-pointer px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400" @click="sortBy('price')">
        <div class="flex items-center gap-3">
            <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                Price
            </p>
            <span class="flex flex-col gap-0.5">
                <svg :class="sort.key === 'price' &amp;&amp; sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300">
                  <path d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z" fill="currentColor"></path>
                </svg>

                <svg :class="sort.key === 'price' &amp;&amp; !sort.asc ? 'text-gray-500 dark:text-gray-400' : 'text-gray-300'" width="8" height="5" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-300">
                  <path d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z" fill="currentColor"></path>
                </svg>
              </span>
        </div>
    </th>
    <th class="px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
        Stock
    </th>
    <th class="px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
        Created At
    </th>
    <th class="px-5 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
        <div class="relative">
            <span class="sr-only">Action</span>
        </div>
    </th>--}}
</tr>
