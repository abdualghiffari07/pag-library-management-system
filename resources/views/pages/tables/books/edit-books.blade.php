@extends('layouts.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Edit Buku" />

    <div class="space-y-6">

        <x-tables.basic-tables.books-data.edit-books
            :book="$book"
            :totalQty="$totalQty"
            :borrowedCopies="$borrowedCopies"
            :availableCopies="$availableCopies"
            :activeLoans="$activeLoans"
        />

    </div>

@endsection