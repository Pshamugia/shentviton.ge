@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Cart Item</h2>
        <div class="row">
            <div class="col-md-6 text-center">
                <img src="{{ $images->first_image }}" alt="Front Design" class="img-fluid border rounded shadow">
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ $images->second_image }}" alt="Back Design" class="img-fluid border rounded shadow">
            </div>
        </div>
    </div>
@endsection
