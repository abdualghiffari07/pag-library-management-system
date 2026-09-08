@props([
    'location',
])

<div class="space-y-6">
    <x-common.component-card title="Edit Location">

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-600 dark:border-error-800 dark:bg-error-500/10 dark:text-error-400">

                <ul class="list-disc space-y-1 pl-5">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <form
            action="{{ route('locations.update', $location) }}"
            method="POST"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div>

                <label
                    for="location_name"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                >
                    Location
                    <span class="text-error-500">*</span>
                </label>

                <input
                    id="location_name"
                    type="text"
                    name="location_name"
                    value="{{ old('location_name', $location->location_name) }}"
                    required
                    autofocus
                    autocomplete="off"
                    placeholder="Masukkan nama location"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                >

                @error('location_name')
                    <p class="mt-1.5 text-xs text-error-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div>

                <label
                    for="description"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                >
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Masukkan keterangan location"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                >{{ old('description', $location->description) }}</textarea>

                @error('description')
                    <p class="mt-1.5 text-xs text-error-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-800">

                <a
                    href="{{ route('locations.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </x-common.component-card>
</div>