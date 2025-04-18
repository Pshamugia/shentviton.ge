@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Cart Item</h2>
        <div class="row">
            @if ($images->first_image)
                <div class="col-md-6 text-center">
                   @php
                    //    dd($images->first_image);
                   @endphp
                    <img src="{{ $images->first_image }}" alt="Front Design" class="img-fluid border rounded shadow">
                </div>
            @endif

            @php
            @endphp
            @if ($images->second_image)
                <div class="col-md-6 text-center">
                    <img src="{{ $images->second_image }}" alt="Back Design" class="img-fluid border rounded shadow">
                </div>
            @endif



        </div>
    </div>
@endsection
