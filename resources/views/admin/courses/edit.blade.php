<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Courses / Edit" />

        <div class="space-y-5 sm:space-y-6">

            <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.courses.partials._base', ['course' => $course])
                @include('admin.courses.partials._translatable', ['locales' => App\Helpers\Helper::getLocales(), 'course' => $course])

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="px-8" type="submit">{{ __('Update') }}</x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
