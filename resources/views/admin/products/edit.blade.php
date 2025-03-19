@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->title)

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Product: {{ $product->title }}</h3>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Ensure PUT method is used for updating -->

        <div class="mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $product->title }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Available Colors</label>
            <div id="colorSelection">
                
                @if ($colors->count() > 0)
                    @foreach ($colors as $index => $color)
                    <input type="hidden" name="colors[{{ $index }}][existing_front_image]" value="{{ $color->front_image }}">
<input type="hidden" name="colors[{{ $index }}][existing_back_image]" value="{{ $color->back_image }}">

                        <div class="color-block border p-3 mb-3" style="background-color: #9c9c9c; border-radius: 10px">
                            <div class="mb-2">
                                <label>Color Name</label>
                                <input type="text" name="colors[{{ $index }}][color_name]" class="form-control"
                                    value="{{ $color->color_name ?? '' }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Color Code</label>
                                <input type="color" name="colors[{{ $index }}][color_code]" class="form-control"
                                    value="{{ $color->color_code ?? '#000000' }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Front Image</label><br>
                                @if (!empty($color->front_image))
                                    <img src="{{ asset('storage/' . $color->front_image) }}" width="100" class="border">
                                @else
                                    <p class="text-muted">No image available</p>
                                @endif
                                <input type="file" name="colors[{{ $index }}][front_image]" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-2">
                                <label>Back Image</label><br>
                                @if (!empty($color->back_image))
                                    <img src="{{ asset('storage/' . $color->back_image) }}" width="100" class="border">
                                @else
                                    <p class="text-muted">No image available</p>
                                @endif
                                <input type="file" name="colors[{{ $index }}][back_image]" class="form-control" accept="image/*">
                            </div>
                            <button type="button" class="btn btn-danger remove-color">Remove</button>
                        </div>
                    @endforeach
                @else
                    <p class="text-danger">No colors found for this product.</p>
                @endif
            </div>
            
            
            
            <button type="button" class="btn btn-primary mt-2" id="addColor">Add New Color</button>
        </div>

        <div class="mb-3">
            <label for="full_text" class="form-label">Full Text</label>
            <textarea name="full_text" id="full_text" class="form-control">{{ $product->full_text }}</textarea>
        </div>

        <div class="mb-3">
            <label for="size" class="form-label">Size</label>
            <input type="text" name="size" id="size" class="form-control" value="{{ $product->size }}">
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (GEL)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <!-- Image Upload with Preview -->
        <div class="mb-3">
            <label for="image1" class="form-label">Main Image</label><br>
            <img src="{{ asset('storage/' . $product->image1) }}" width="80">
            <input type="file" name="image1" id="image1" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image2" class="form-label">Additional Image 2</label><br>
            @if ($product->image2)
                <img src="{{ asset('storage/' . $product->image2) }}" width="80">
            @endif
            <input type="file" name="image2" id="image2" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image3" class="form-label">Additional Image 3</label><br>
            @if ($product->image3)
                <img src="{{ asset('storage/' . $product->image3) }}" width="80">
            @endif
            <input type="file" name="image3" id="image3" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image4" class="form-label">Additional Image 4</label><br>
            @if ($product->image4)
                <img src="{{ asset('storage/' . $product->image4) }}" width="80">
            @endif
            <input type="file" name="image4" id="image4" class="form-control">
        </div>


        <div class="mb-3">
            <label for="type" class="form-label">Product Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="">-- Select Type --</option>
                <option value="shirt" {{ $product->type == 'shirt' ? 'selected' : '' }}>Shirt</option>
                <option value="hat" {{ $product->type == 'hat' ? 'selected' : '' }}>Hat</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script>
document.getElementById('addColor').addEventListener('click', function () {
    let colorContainer = document.getElementById('colorSelection');
    let colorIndex = colorContainer.children.length;

    let colorBlock = document.createElement('div');
    colorBlock.classList.add('color-block');
    colorBlock.innerHTML = `
        <div class="mb-2">
            <label>Color Name</label>
            <input type="text" name="colors[${colorIndex}][color_name]" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Color Code</label>
            <input type="color" name="colors[${colorIndex}][color_code]" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Upload Front Image</label>
            <input type="file" name="colors[${colorIndex}][front_image]" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-2">
            <label>Upload Back Image</label>
            <input type="file" name="colors[${colorIndex}][back_image]" class="form-control" accept="image/*" required>
        </div>
        <button type="button" class="btn btn-danger remove-color">Remove</button>
    `;

    colorContainer.appendChild(colorBlock);

    // Remove color block when clicked
    colorBlock.querySelector('.remove-color').addEventListener('click', function () {
        colorBlock.remove();
    });
});

// Handle removing existing color blocks
document.querySelectorAll('.remove-color').forEach(button => {
    button.addEventListener('click', function () {
        this.closest('.color-block').remove();
    });
});
</script>

@endsection
