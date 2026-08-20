@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Penulis" />

    <div class="space-y-6">
        <x-tables.basic-tables.authors />
    </div>
@endsection