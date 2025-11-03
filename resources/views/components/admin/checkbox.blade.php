@props(['name', 'model', 'text'])

<label for="{{$name}}" class="mt-2 inline-flex items-center gap-2 cursor-pointer select-none">
    <input type="hidden" name="{{$name}}" value="0">
    <input type="checkbox" id="{{$name}}" name="{{$name}}" value="1" class="sr-only" x-model="{{$model}}">
    <span
        class="flex h-5 w-5 items-center justify-center rounded-sm border-[1.25px]"
        :class="{{$model}} ? 'bg-blue-button bg-blue-border' : 'border-gray-300 dark:border-gray-700'"
        aria-hidden="true"
    >
        <span :class="{{$model}} ? 'opacity-100' : 'opacity-0'" class="transition-opacity">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="1.6666" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    </span>
    <span class="text-sm text-gray-700 dark:text-gray-400">{{$text}}</span>
</label>
