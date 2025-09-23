@props(['paginator'])

@php
    $append = request()->query();
    $first  = $paginator->firstItem() ?? 0;
    $last   = $paginator->lastItem() ?? 0;
    $total  = $paginator->total();
    $current = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
@endphp

<div class="flex flex-col items-center justify-between border-t border-gray-200 px-5 py-4 sm:flex-row dark:border-gray-800">
    {{-- Showing X to Y of Z --}}
    <div class="pb-3 sm:pb-0">
        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">
            Showing <span class="text-gray-800 dark:text-white/90">{{ $first }}</span>
            to <span class="text-gray-800 dark:text-white/90">{{ $last }}</span>
            of <span class="text-gray-800 dark:text-white/90">{{ $total }}</span>
        </span>
    </div>

    {{-- Pagination Controls --}}
    <div class="flex items-center gap-2">
        {{-- Prev Button --}}
        <a href="{{ $paginator->previousPageUrl() ? $paginator->appends($append)->previousPageUrl() : '#' }}"
           class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-800 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 {{ $paginator->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
            &larr;
        </a>

        {{-- Page Numbers --}}
        <ul class="flex items-center gap-1">
            @for ($page = 1; $page <= $lastPage; $page++)
                <li>
                    <a href="{{ $paginator->appends($append)->url($page) }}"
                       class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium
                       {{ $page === $current
                           ? 'bg-brand-500 text-white'
                           : 'text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white' }}">
                        {{ $page }}
                    </a>
                </li>
            @endfor
        </ul>

        {{-- Next Button --}}
        <a href="{{ $paginator->nextPageUrl() ? $paginator->appends($append)->nextPageUrl() : '#' }}"
           class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-800 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 {{ $current === $lastPage ? 'pointer-events-none opacity-50' : '' }}">
            &rarr;
        </a>
    </div>
</div>
