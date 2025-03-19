@extends('layouts.admin')

@section('title', 'Add a New Product')

@section('content')
<div class="container">
    <h3 class="mb-4">Add a New Product</h3>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Available Colors</label>
            <div id="colorSelection">
                <!-- Colors will be dynamically added here -->
            </div>
            <button type="button" class="btn btn-primary mt-2" id="addColor">Add Color</button>
        </div>




        <div class="mb-3">
            <label for="full_text" class="form-label">Full Text</label>
            <textarea name="full_text" id="full_text" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="size" class="form-label">Size</label>
            <input type="text" name="size" id="size" class="form-control">
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (GEL)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image1" class="form-label">Main Image</label>
            <input type="file" name="image1" id="image1" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image2" class="form-label">Additional Image 2</label>
            <input type="file" name="image2" id="image2" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image3" class="form-label">Additional Image 3</label>
            <input type="file" name="image3" id="image3" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image4" class="form-label">Additional Image 4</label>
            <input type="file" name="image4" id="image4" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Product</button>
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
    </script>
    


@endsection
