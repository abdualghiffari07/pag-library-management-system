@extends('layouts.app')

@section('content')

    <x-tables.basic-tables.books-data.add-books
        :authors="$authors"
        :equipments="$equipments"
    />

@endsection