@props(['locales' => [], 'group' => 'Additional Fields', 'count' => false])

<div class="px-5 py-3 sm:px-6 sm:py-4 flex items-center border-top-radius bg-ghostwhite dark:bg-gray-dark">
    <h3 class="py-1 text-base font-medium text-gray-800 dark:text-white/90">{{ $group }}</h3>
    @if(!empty($locales))
        <nav class="m-0! flex space-x-2 overflow-x-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar]:h-1.5">
            @foreach($locales as $code => $label)
                <button type="button" class="inline-flex items-center gap-2 border-b-2 px-2.5 py-1 text-sm font-medium transition-colors duration-200 ease-in-out text-blue-button text-blue-border dark:text-brand-400 dark:border-brand-400" :class="activeTab === '{{$code}}' ? ' text-blue-button text-blue-border  dark:text-brand-400 dark:border-brand-400' : 'bg-transparent text-gray-500 border-transparent  hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" @click="activeTab = '{{$code}}'">
                    <span class="size-5" aria-hidden="true"></span>
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    @endif

    @if(false !== $count)
        <span class="text-sm">&nbsp;({{$count}})</span>
    @endif
</div>
