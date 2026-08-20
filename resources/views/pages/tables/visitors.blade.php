@extends('layouts.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Daftar Pengunjung" />

    <div class="space-y-6">
        <x-tables.basic-tables.visitors />
    </div>

@endsection