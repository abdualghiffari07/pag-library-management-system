@extends('layouts.app')

@section('content')

<x-tables.basic-tables.books-data.edit-books
    :book="$book"
    :total-qty="$totalQty"
    :borrowed-copies="$borrowedCopies"
    :available-copies="$availableCopies"
    :active-loans="$activeLoans"
    :equipments="$equipments"
    :authors="$authors"
/>

@endsection