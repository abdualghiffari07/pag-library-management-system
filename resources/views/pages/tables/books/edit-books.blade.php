@extends('layouts.app')

@section('content')
    <x-tables.basic-tables.books-data.edit-books
        :book="$book"
        :totalQty="$totalQty"
        :borrowedCopies="$borrowedCopies"
        :availableCopies="$availableCopies"
        :activeLoans="$activeLoans"
        :equipments="$equipments"
        :authors="$authors"
        :visitors="$visitors"
    />
@endsection