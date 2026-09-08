@extends('layouts.app')

@section('content')
    <x-tables.basic-tables.borrowers.index
        :borrowers="$borrowers"
        :search="$search"
    />
@endsection