@extends('layouts.app')

@section('content')
    <x-tables.basic-tables.books-data.add-books
        :locations="$locations"
        :equipments="$equipments"
    />
@endsection