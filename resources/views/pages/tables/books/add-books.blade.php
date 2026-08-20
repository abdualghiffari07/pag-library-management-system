@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Buku" />

    <div class="space-y-6">
        <x-tables.basic-tables.books-data.add-books />
    </div>
@endsection