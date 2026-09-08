@extends('layouts.app')

@section('content')
    <x-tables.basic-tables.locations.index
        :locations="$locations"
    />
@endsection