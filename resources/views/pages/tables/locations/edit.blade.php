@extends('layouts.app')

@section('content')
    <x-tables.basic-tables.locations.edit
        :location="$location"
    />
@endsection