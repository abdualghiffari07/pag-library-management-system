@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Daftar Buku" />

    <div class="space-y-6">
        <x-tables.basic-tables.data-books />
    </div>
@endsection