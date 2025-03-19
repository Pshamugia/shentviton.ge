@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Upload Clipart</h3>

    <form action="{{ route('admin.cliparts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Select Image</label>
            <input type="file" id="image" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-control" required>
                <option value="all">All</option>
                <option value="sport">Sport</option>
                <option value="funny">Funny</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
@endsection
