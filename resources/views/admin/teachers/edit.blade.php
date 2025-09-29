<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Teacher') }}
        </h2>
    </x-slot>

    <div class="customers-page admin-page">
        {{-- Breadcrumbs --}}
        <x-admin.breadcrumbs page="Teachers / Edit" />

        <div class="space-y-5 sm:space-y-6">

            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" onsubmit="cleanEmptyTranslations(this)">
                @csrf
                @method('PUT')

                @include('admin.teachers.partials._base', ['teacher' => $teacher])
                @include('admin.teachers.partials._translatable', ['locales' => App\Helpers\Helper::getLocales(), 'teacher' => $teacher])

                <div class="mt-6">
                    <x-primary-button class="px-8" type="submit">{{ __('Update') }}</x-primary-button>
                    <a class="ml-3 text-gray-600 hover:underline" href="{{ route('admin.teachers.index') }}">{{ __('Cancel') }}</a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
