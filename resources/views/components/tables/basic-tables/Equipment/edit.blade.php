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

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        EQUIPMENT
                    </label>

                    <input
                        type="text"
                        name="equipment_name"
                        value="{{ old('equipment_name', $equipment->equipment_name) }}"
                        required
                        autofocus
                        placeholder="Masukkan nama equipment"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                    @error('equipment_name')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- INFO --}}
                @if ($equipment->books_count ?? false)
                    <div class="rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 dark:border-warning-800 dark:bg-warning-500/10">
                        <p class="text-sm text-warning-700 dark:text-warning-400">
                            Equipment ini sedang digunakan oleh buku.
                        </p>
                    </div>
                @endif

                {{-- ACTIONS --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-800">

                    <a
                        href="{{ route('equipment.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
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