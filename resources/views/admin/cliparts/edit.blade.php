@extends('layouts.admin')

@section('title', 'Edit Clipart')

@section('content')
<div class="container">
    <h3>Edit Clipart</h3>

    <form action="{{ route('admin.cliparts.update', $clipart->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Image preview --}}
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="{{ asset('storage/' . $clipart->image) }}" width="150" class="mb-2" style="border:1px solid #ccc"><br>
            <label for="image" class="form-label">Change Image (optional)</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>

        {{-- Category select --}}
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-control" required>
                <option value="all" {{ $clipart->category == 'all' ? 'selected' : '' }}>ყველა</option>
                <option value="sport" {{ $clipart->category == 'sport' ? 'selected' : '' }}>სპორტი</option>
                <option value="cars" {{ $clipart->category == 'cars' ? 'selected' : '' }}>მანქანები</option>
                <option value="funny" {{ $clipart->category == 'funny' ? 'selected' : '' }}>სახალისო</option>
                <option value="love" {{ $clipart->category == 'love' ? 'selected' : '' }}>სასიყვარულო</option>
                <option value="animation" {{ $clipart->category == 'animation' ? 'selected' : '' }}>ანიმაციური გმირები</option>
                <option value="animals" {{ $clipart->category == 'animals' ? 'selected' : '' }}>ცხოველთა სამყარო</option>
                <option value="emoji" {{ $clipart->category == 'emoji' ? 'selected' : '' }}>ემოჯები</option>
                <option value="tigerskin" {{ $clipart->category == 'tigerskin' ? 'selected' : '' }}>ვეფხისტყაოსანი</option>
                <option value="mamapapuri" {{ $clipart->category == 'mamapapuri' ? 'selected' : '' }}>მამაპაპური</option>
                <option value="qartuli" {{ $clipart->category == 'qartuli' ? 'selected' : '' }}>ქართული თემა</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
