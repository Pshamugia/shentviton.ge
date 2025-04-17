<div id="text" class="tabcontent">
    <div class="side-modals" style="padding:5px !important; background-color:#ccc">
        <div class="customization-boxs">
            <div id="textInputsContainer">
            </div>
            <button type="button" id="addTextInput" class="btn btn-primary my-2">+ Add Text</button>
            <div class="mb-3">
                <label for="text_color" class="form-label">ტექსტის ფერი</label>
                <input type="color" id="text_color" class="color-picker">
            </div>
            <div class="mb-3">
                <label class="form-label">ტექსტის სტილი</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-dark text-style-btn" data-style="bold" title="Bold">
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
                <label for="font_family" class="form-label">ფონტები</label>
                <select id="font_family" class="chosen-select" data-placeholder="აირჩიეთ ფონტი">
                    <option value=""></option>
                    <option value="Arial">Arial</option>
                    <option value="Lobster-Regular">Lobster-Regular</option>
                    <option value="Orbitron">Orbitron</option>
                    <option value="Alk-rounded" style="font-family: 'alk-rounded', sans-serif !important;">
                        <al> Alk-rounded </al>
                    </option>
                    <option value="PlaywriteIN" style="font-family: 'PlaywriteIN', sans-serif !important;">
                        PlaywriteIN</option>
                    <option value="Lobster-Regular" style="font-family: 'Lobster-Regular', sans-serif !important;">
                        Lobster-Regular
                    </option>
                    <option value="Orbitron" style="font-family: 'Orbitron', sans-serif !important;">
                        Orbitron
                    </option>
                    <option value="Orbitron">Orbitron</option>
                    <option value="EricaOne" style="font-family: 'EricaOne', sans-serif !important;">
                        EricaOne
                    </option>
                    <option value="GloriaHallelujah" style="font-family: 'GloriaHallelujah', sans-serif !important;">
                        GloriaHallelujah
                    </option>
                    <option value="Creepster" style="font-family: 'Creepster', sans-serif !important;">
                        Creepster</option>
                    <option value="RubikBubbles" style="font-family: 'RubikBubbles', sans-serif !important;">
                        RubikBubbles</option>
                    <option value="BerkshireSwash" style="font-family: 'BerkshireSwash', sans-serif !important;">
                        BerkshireSwash
                    </option>
                    <option value="Monoton" style="font-family: 'Monoton', sans-serif !important;">Monoton
                    </option>
                    <option value="BlackOpsOne" style="font-family: 'BlackOpsOne', sans-serif !important;">
                        BlackOpsOne</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="font_size" class="form-label">ფონტის ზომა</label>
                <input type="number" id="font_size" class="form-control input-styled" value="30" min="10"
                    max="100">
            </div>
        </div>
    </div>
</div>
