@extends('layouts.admin')

@section('title', 'clipart')
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
                <option value="all">ყველა</option>
                            <option value="sport">სპორტი</option>
                            <option value="cars">მანქანები</option>
                            <option value="funny">სახალისო</option>
                            <option value="love">სასიყვარულო</option>
                            <option value="animation">ანიმაციური გმირები</option>
                            <option value="animals">ცხოველთა სამყარო</option>
                            <option value="emoji">ემოჯები</option>
                            <option value="tigerskin">ვეფხისტყაოსანი</option>
                            <option value="mamapapuri">მამაპაპური</option> 
                            <option value="qatuli">ქართული თემა</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
@endsection
