@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <x-common.component-card title="Edit Equipment">

            <form
                action="{{ route('equipment.update', $equipment->equipment_id) }}"
                method="POST"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                {{-- NAMA EQUIPMENT --}}
                <div>
                    <label
                        for="equipment_name"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Nama Equipment
                    </label>

                    <input
                        type="text"
                        id="equipment_name"
                        name="equipment_name"
                        value="{{ old('equipment_name', $equipment->equipment_name) }}"
                        placeholder="Masukkan nama equipment"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                        required
                    >

                    @error('equipment_name')
                        <p class="mt-2 text-sm text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- INFORMASI --}}
                <div class="rounded-lg bg-gray-50 px-4 py-3 dark:bg-white/[0.03]">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Equipment:
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            {{ $equipment->equipment_name }}
                        </span>
                    </p>
                </div>

                {{-- BUTTON --}}
                <div class="flex items-center justify-end gap-3">

                    <a
                        href="{{ route('equipment.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]"
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
@endsection