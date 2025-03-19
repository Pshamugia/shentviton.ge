@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Manage Cliparts</h3>
    <a href="{{ route('admin.cliparts.create') }}" class="btn btn-primary mb-3">Upload Clipart</a>

    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cliparts as $clipart)
            <tr>
                <td><img src="{{ asset('storage/' . $clipart->image) }}" width="80"></td>
                <td>{{ ucfirst($clipart->category) }}</td>
                <td>
                    <form action="{{ route('admin.cliparts.destroy', $clipart->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
