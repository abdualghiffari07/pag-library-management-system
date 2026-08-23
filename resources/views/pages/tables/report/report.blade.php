@extends('layouts.app')

@section('content')

    @include('components.tables.basic-tables.report.report-content', [
        'totalBooks' => $totalBooks,
        'borrowedBooks' => $borrowedBooks,
        'availableBooks' => $availableBooks,
        'visitors' => $visitors,
        'chartMonths' => $chartMonths,
        'loanData' => $loanData,
        'visitorData' => $visitorData,
    ])

@endsection