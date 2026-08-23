@extends('layouts.app')

@section('content')

    <x-tables.basic-tables.authors-data.edit-author :author="$author" />

@endsection