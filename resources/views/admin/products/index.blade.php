@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="container">
    <h3 class="mb-4">Manage Products</h3>

    <a href="{{ route('admin.products.create') }}" class="btn btn-success">Add Product</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>subtype</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->title }}</td>
                <td>$ {{ number_format($product->price, 2) }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->subtype }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirmDelete()" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <script>
                        function confirmDelete() {
                            return confirm('დარწმუნებული ხარ რომ გსურს ამ პროდუქტის წაშლა?'); // "Are you sure you want to delete this clipart?"
                        }
                    </script>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
