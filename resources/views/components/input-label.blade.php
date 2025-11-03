@props(['value', 'lang' => false])

@php
    // Check if lang is provided as Blade prop (server-side)
    $hasLangProp = !empty($lang);
    // Check if lang will be bound via Alpine.js (client-side) - need to preserve the binding
    $hasAlpineLang = $attributes->has('x-bind:lang') || $attributes->has(':lang');
    // Get the Alpine bound expression if present
    $alpineLangExpr = $attributes->get('x-bind:lang') ?: $attributes->get(':lang');
@endphp

<label {{ $attributes->except(['lang', 'x-bind:lang', ':lang'])->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2']) }}
       @if($hasAlpineLang) x-bind:lang="{{ $alpineLangExpr }}" @endif>
    {{ $value ?? $slot }}

    {{-- Server-side lang (Blade prop) --}}
    @if($hasLangProp && !$hasAlpineLang)
        <sup>({{ $lang }})</sup>
    @endif

    {{-- Client-side lang (Alpine.js bound) - use the bound value directly --}}
    @if($hasAlpineLang)
        <sup x-show="{{ $alpineLangExpr }}" x-text="`(${ {{ $alpineLangExpr }} })`"></sup>
    @endif
</label>
