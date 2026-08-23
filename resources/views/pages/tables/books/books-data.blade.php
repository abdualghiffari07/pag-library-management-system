@extends('layouts.app')

@section('content')

    <div class="space-y-6">
        <x-tables.basic-tables.books-data.books-data :books="$books" />
    </div>
@endsection