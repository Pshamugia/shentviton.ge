@extends('layouts.admin')

@section('title', 'Add a New Product')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

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

            <div class="mb-3">
                <label for="type" class="form-label">Product Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="მაისური">მაისური</option>
                    <option value="პოლო">პოლო მაისური</option>
                    <option value="ბომბერი">ბომბერი</option>
                    <option value="ჰუდი">ჰუდი</option>
                    <option value="ჩანთა">ჩანთა</option>
                    <option value="კეპი">კეპი</option>
                    <option value="ქეისი">ტელეფონის ქეისი</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="size" class="form-label">Sizes</label>
                <select name="size[]" id="size" class="form-control" multiple>
                    <!-- Default options for მაისური -->
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">2XL</option>
                </select>
            </div>

            <script>
                $(document).ready(function() {
                    const sizeSelect = $('#size');
                    sizeSelect.chosen({
                        width: '100%',
                        placeholder_text_multiple: 'აირჩიეთ ზომები'
                    });

                    const iphoneModels = [
                        'iPhone 7', 'iPhone 7 Plus', 'iPhone 8', 'iPhone 8 Plus',
                        'iPhone X', 'iPhone XR', 'iPhone XS', 'iPhone XS Max',
                        'iPhone 11', 'iPhone 11 Pro', 'iPhone 11 Pro Max',
                        'iPhone 12', 'iPhone 12 Mini', 'iPhone 12 Pro', 'iPhone 12 Pro Max',
                        'iPhone 13', 'iPhone 13 Mini', 'iPhone 13 Pro', 'iPhone 13 Pro Max',
                        'iPhone 14', 'iPhone 14 Plus', 'iPhone 14 Pro', 'iPhone 14 Pro Max',
                        'iPhone 15', 'iPhone 15 Plus', 'iPhone 15 Pro', 'iPhone 15 Pro Max'
                    ];

                    $('#type').on('change', function() {
                        const selectedType = $(this).val();
                        let options = '';

                        if (selectedType === 'მაისური') {
                            options = `
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                        `;
                        } else if (selectedType === 'პოლო') {
                            options = `
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                        `;
                        } else if (selectedType === 'ჰუდი') {
                            options = `
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                        `;
                        } else if (selectedType === 'კეპი') {
                            options = `
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="7.5">7.5</option>
                        `;
                        } else if (selectedType === 'ქეისი') {
                            iphoneModels.forEach(model => {
                                options += `<option value="${model}">${model}</option>`;
                            });
                        }

                        sizeSelect.empty().append(options);
                        sizeSelect.trigger("chosen:updated");
                    });
                });
            </script>

            <div class="form-group">
                <label>Subtype</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="subtype" id="მზა" value="მზა"
                        {{ old('subtype', $product->subtype ?? '') == 'მზა' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="მზა">მზა</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="subtype" id="custom" value="custom"
                        {{ old('subtype', $product->subtype ?? '') == 'custom' ? 'checked' : '' }}>
                    <label class="form-check-label" for="custom">custom</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>

    <script>
        document.getElementById('addColor').addEventListener('click', function() {
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
                <input type="file" name="colors[${colorIndex}][front_image]" class="form-control" accept="image/*">
            </div>
            <div class="mb-2">
                <label>Upload Back Image</label>
                <input type="file" name="colors[${colorIndex}][back_image]" class="form-control" accept="image/*">
            </div>
            <button type="button" class="btn btn-danger remove-color">Remove</button>
        `;

            colorContainer.appendChild(colorBlock);

            // Remove color block when clicked
            colorBlock.querySelector('.remove-color').addEventListener('click', function() {
                colorBlock.remove();
            });
        });
    </script>



@endsection
