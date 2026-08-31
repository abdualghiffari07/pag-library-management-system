@extends('layouts.app')

@section('content')

@include('components.tables.basic-tables.report.report-content', [
    'totalBooks' => $totalBooks,
    'borrowedBooks' => $borrowedBooks,
    'availableBooks' => $availableBooks,
    'visitors' => $visitors,
    'workerVisitors' => $workerVisitors,
    'studentVisitors' => $studentVisitors,
    'guestVisitors' => $guestVisitors,
    'otherVisitors' => $otherVisitors,
    'chartMonths' => $chartMonths,
    'loanData' => $loanData,
    'visitorData' => $visitorData,
    'startDate' => $startDate,
    'endDate' => $endDate,
    'groupByDay' => $groupByDay,
])

@endsection