@extends('layouts.app')

@section('content')

    <x-common.page-breadcrumb
        pageTitle="Penulis"
    />

    <x-tables.basic-tables.authors-data.authors
        :authors="$authors"
        :search="$search"
    />

@endsection