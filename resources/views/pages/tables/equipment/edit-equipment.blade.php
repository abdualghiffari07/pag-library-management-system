@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <x-common.component-card title="Edit Equipment">
            <x-tables.basic-tables.Equipment.edit-equipment
                :equipment="$equipment"
            />
        </x-common.component-card>
    </div>
@endsection