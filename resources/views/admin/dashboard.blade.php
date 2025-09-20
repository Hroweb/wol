<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="bg-white border rounded-lg p-4">Card</div>
            <div class="bg-white border rounded-lg p-4">Card</div>
            <div class="bg-white border rounded-lg p-4">Card</div>
            <div class="bg-white border rounded-lg p-4">Card</div>
        </div>

        <div class="grid lg:grid-cols-12 gap-3 mt-3">
            <div class="lg:col-span-7">
                <div class="bg-white border rounded-lg p-4 h-[360px]">Chart area</div>
            </div>
            <div class="lg:col-span-5">
                <div class="bg-white border rounded-lg p-4 h-full">Side card</div>
            </div>
        </div>
    </div>
    {{--<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>--}}
</x-app-layout>
