@extends('layouts.app')
@section('title', 'Customize ' . $product->title)
@section('content')
    <div class="container">
        <h3 class="mb-4">Customize Your {{ $product->title }}</h3>
        <div class="row">
            <div class="col-md-4" style="background-color: #ada0a0">

                <form id="customizationForm">
                    <button type="button" id="toggleUploadSidebar" class="upload-btn">
                        <i class="fas fa-upload"></i> Upload Image
                    </button>

                    <div id="uploadSidebar">
                        <div class="upload-header">
                            <button id="closeUploadSidebar" class="close-btn">&times;</button>
                            <h4>Upload Your Image</h4>
                        </div>
                        <input type="file" id="uploaded_image" class="form-control">
                        <div id="imagePreviewContainer"></div>
                    </div>
                    <button id="toggleClipartSidebar" class="clipart-btn">
                        <i class="fas fa-palette"></i> Cliparts
                    </button>

                    <div id="clipartSidebar">
                        <div class="clipart-header">
                            <button id="closeClipartSidebar" class="close-btn">&times;</button>
                            <input type="text" id="searchCliparts" class="form-control"
                                placeholder="🔍 კლიპარტების ძიება">
                            <select id="clipartCategory">
                                <option value="all">ყველა კატეგორია</option>
                                <option value="sport">სპორტი</option>
                                <option value="funny">სასაცილო</option>
                                <option value="nature">ბუნება</option>
                                <option value="animals">ცხოველები</option>
                            </select>
                        </div>
                        <div id="clipartContainer">
                            @foreach ($cliparts as $clipart)
                                <div class="clipart-item">
                                    <img class="clipart-img" data-category="{{ $clipart->category }}"
                                        data-image="{{ asset('storage/' . $clipart->image) }}"
                                        src="{{ asset('storage/' . $clipart->image) }}" alt="Clipart">
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div> 
<!-- Text Customization Section (Styled) -->
                    <!-- Text Button -->
                    <button id="toggleTextSidebar" class="upload-btn">
                        <i class="fas fa-font"></i> ტექსტი
                    </button>
                    </div>

                     <!-- Text Customization Sidebar -->
                     <div id="textSidebar" class="side-modal" style="width:270px !important; padding:5px !important">
                        <button id="closeTextSidebar" class="close-btn">&times;</button>
                        <h4>Customize Text</h4>
                    <div class="customization-box">
                        <div class="mb-3">
                            <label for="top_text" class="form-label">Top Text</label>
                            <input type="text" id="top_text" class="form-control input-styled"
                                placeholder="Enter top text">
                        </div>
                        <div class="mb-3">
                            <label for="bottom_text" class="form-label">Bottom Text</label>
                            <input type="text" id="bottom_text" class="form-control input-styled"
                                placeholder="Enter bottom text">
                        </div>
                        <div class="mb-3">
                            <label for="text_color" class="form-label">Text Color</label>
                            <input type="color" id="text_color" class="color-picker">
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Text Style</label>
                            <div class="btn-group text-style-group">
                                <button type="button" class="btn btn-outline-dark text-style-btn" data-style="bold"
                                    title="Bold">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button type="button" class="btn btn-outline-dark text-style-btn" data-style="italic"
                                    title="Italic">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button type="button" class="btn btn-outline-dark text-style-btn" data-style="underline"
                                    title="Underline">
                                    <i class="fas fa-underline"></i>
                                </button>
                                <button type="button" class="btn btn-outline-dark text-style-btn" data-style="curved">
                                    <i class="fas fa-circle-notch"></i> <br> წრე
                                </button>
                                <button type="button" class="btn btn-outline-dark text-style-btn" data-style="normal"
                                    title="Reset">
                                    <i class="fas fa-undo"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="font_family" class="form-label">Font Family</label>
                           <select id="font_family" class="form-control input-styled">
                                    <option value="Arial">Arial</option>
                                    <option value="Lobster-Regular">Lobster-Regular</option>
                                    <option value="Orbitron">Orbitron</option>
                                    <option value="Alk-rounded"
                                        style="font-family: 'alk-rounded', sans-serif !important;">
                                        <al> Alk-rounded </al>
                                    </option>
                                    <option value="PlaywriteIN"
                                        style="font-family: 'PlaywriteIN', sans-serif !important;">
                                        PlaywriteIN</option>
                                    <option value="Lobster-Regular"
                                        style="font-family: 'Lobster-Regular', sans-serif !important;">Lobster-Regular
                                    </option>
                                    <option value="Orbitron" style="font-family: 'Orbitron', sans-serif !important;">
                                        Orbitron
                                    </option>
                                    <option value="Orbitron">Orbitron</option>
                                    <option value="EricaOne" style="font-family: 'EricaOne', sans-serif !important;">
                                        EricaOne
                                    </option>
                                    <option value="GloriaHallelujah"
                                        style="font-family: 'GloriaHallelujah', sans-serif !important;">GloriaHallelujah
                                    </option>
                                    <option value="Creepster" style="font-family: 'Creepster', sans-serif !important;">
                                        Creepster</option>
                                    <option value="RubikBubbles"
                                        style="font-family: 'RubikBubbles', sans-serif !important;">
                                        RubikBubbles</option>
                                    <option value="BerkshireSwash"
                                        style="font-family: 'BerkshireSwash', sans-serif !important;">BerkshireSwash
                                    </option>
                                    <option value="Monoton" style="font-family: 'Monoton', sans-serif !important;">Monoton
                                    </option>
                                    <option value="BlackOpsOne"
                                        style="font-family: 'BlackOpsOne', sans-serif !important;">
                                        BlackOpsOne</option>
                                </select>
                        </div>
                        <div class="mb-3">
                            <label for="font_size" class="form-label">Font Size</label>
                            <input type="number" id="font_size" class="form-control input-styled" value="30"
                                min="10" max="100">
                        </div>
                    </div>
                     </div>
                    <button id="saveDesign" class="btn save-btn">Save Design</button>
                    <button id="addToCart" class="btn save-btn" style="display: none">Add to Cart</button>

                    <a id="previewDesign" class="btn save-btn" style="display: none">Preview Design</a>
                </form>
            </div>
            <div class="col-md-8 d-flex align-items-center justify-content-center"
                style="background-color: #f0f0f0; height: 100vh; position: relative;">
                <div id="design-area" style="position: relative; width: 100%; max-width: 500px;">
                    <img id="product-image"
                        data-default-image="{{ asset('storage/' . $product->image1) }}"
                        src="{{ asset('storage/' . $product->image1) }}"
                        alt="{{ $product->title }}"
                        data-id="{{ $product->id }}"
                        style="width: 100%; height: auto; display: none;" data-type={{ $product->type }}>

                    <canvas id="tshirtCanvas"></canvas>
 <style> 
.color-container {
    display: flex;
    align-items: center; /* Aligns both sides vertically */
    justify-content: center; /* Keeps everything centered */
    gap: 40px; /* Space between sections */
}

.color-box,
.side-box {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center content */
    text-align: center;
}

.label {
    font-weight: bold;
    margin-bottom: 5px; /* Adds space between text and buttons */
}

.colors {
    display: flex;
    gap: 10px; /* Space between color buttons */
}

.switch-buttons {
    display: flex;
    gap: 10px; /* Space between Front/Back buttons */
}


</style>
                    @if (!empty($productArray['colors']) && count($productArray['colors']) > 0)
                    <div class="color-selection" style="top: -100px !important; position: relative;">
                        <div class="color-container">
                            <!-- Color Selection (Left Side) -->
                            <div class="color-box">
                                <p class="label">აირჩიეთ ფერი:</p>
                                <div class="colors">
                                    @foreach ($productArray['colors'] as $color)
                                        <button class="color-option" data-color="{{ $color['color_code'] }}"
                                            data-front-image="{{ asset('storage/' . $color['front_image']) }}"
                                            data-back-image="{{ asset('storage/' . $color['back_image']) }}"
                                            data-back-index={{ 'back-' . $color['id'] }}
                                            data-front-index={{ 'front-' . $color['id'] }} data-index={{ $color['id'] }}
                                            style="background-color: {{ $color['color_code'] }}; width: 40px; height: 40px; border-radius: 50%; border: 2px solid #000;">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                    
                            <!-- Side Selection (Right Side) -->
                            <div class="side-box">
                                <p class="label">აირჩიეთ მხარე:</p>
                                <div class="switch-buttons">
                                    <button id="showFront" class="btn btn-primary" data-image="">წინა</button>
                                    <button id="showBack" class="btn btn-secondary" data-image="">უკანა</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script> 
        var textSidebar = document.getElementById("textSidebar");
        var toggleTextButton = document.getElementById("toggleTextSidebar");
        var closeTextButton = document.getElementById("closeTextSidebar");
    
        // Open Sidebar
        toggleTextButton.addEventListener("click", function(event) {
            event.preventDefault();
            textSidebar.classList.add("active");
        });
    
        // Close Sidebar when clicking the close button
        closeTextButton.addEventListener("click", function(event) {
            event.preventDefault(); // Prevents form submission
            textSidebar.classList.remove("active");
        });
     
    
        // Close Sidebar when clicking outside
        document.addEventListener("click", function(event) {
            if (!textSidebar.contains(event.target) && event.target !== toggleTextButton) {
                textSidebar.classList.remove("active");
            }
        });
    
        // Prevent sidebar from closing when clicking inside
        textSidebar.addEventListener("click", function(event) {
            event.stopPropagation();
        });</script>
@endsection
