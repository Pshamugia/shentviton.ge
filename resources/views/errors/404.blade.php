@extends('layouts.app')

@section('title', 'გვერდი არ არსებობს | Shentviton')

@section('content')
<div class="text-center mt-5 mb-5">
    <img src="{{ asset('storage/designs/404.png') }}" alt="404 გვერდი არ არსებობს" class="img-fluid" style="max-width: 400px;">
    
    <h1 class="mt-4">404 - გვერდი არ არსებობს</h1>
    <p class="lead">უკაცრავად, თქვენ ეძებთ გვერდს რომელიც არ მოიძებნა.</p>
    
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
        მთავარ გვერდზე დაბრუნება
    </a>
</div>
@endsection
