@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        <x-common.component-card title="Data Penulis">

            <x-tables.basic-tables.authors-data.authors
                :authors="$authors"
            />

        </x-common.component-card>

    </div>

@endsection