@props(['value', 'lang' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if($lang)
        <sup>({{ $lang }})</sup>
    @endif
</label>
