@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ $title }}
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Tambahkan equipment baru ke dalam sistem perpustakaan.
            </p>
        </div>

        <x-tables.basic-tables.Equipment.add-equipment />
    </div>
@endsection