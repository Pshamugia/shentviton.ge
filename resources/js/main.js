import toggleSideBar from "./utils";
import getCanvasDefaults from "./defaults.js";
const canvas = new fabric.Canvas("tshirtCanvas");
const product_image = document.querySelector("#product-image");
const designArea = document.querySelector("#design-area");
const product_type = product_image?.getAttribute("data-type") || "default";
const default_stroke_width = 2;
const default_stoke_fill = "#ccc";
const product_id = product_image?.getAttribute("data-id");
const front_state_key = `${product_id}.front_design`;
const back_state_key = `${product_id}.back_design`;

const rand_key = Math.random().toString(36).substring(7);

let text_objects = {};
let cbtns = [...document.querySelectorAll(".text-style-btn")];

let state = {
    current_image_url: "",
    current_image_side: "front",
};

let color_chosen = false;

const form = {
    font_family: document.querySelector("#font_family"),
    font_size: document.querySelector("#font_size"),
    text_color: document.querySelector("#text_color"),
    btns: cbtns,
    text_container: document.querySelector("#textInputsContainer"),
    add_text_btn: document.querySelector("#addTextInput"),
};

let selectedFrontImage = "";
let selectedBackImage = "";

let active_text_obj = null;
let designGroup;
let originalAdd;

export default function main() {
    // NOT IN USE
    sidebarHandler();
    initCanvas();
    initForm();
    initGlobalEvents();
}

function initCanvas() {
    resizeCanvas(true);
    window.addEventListener("resize", resizeCanvas(true));
    initProductImage();
    addInnerBorder();
}

function initGlobalEvents() {
    window.addEventListener("beforeunload", clearLocalStorageOnExit);
    function clearLocalStorageOnExit() {
        Object.keys(localStorage).forEach((key) => {
            if (
                key.includes("/colors/") ||
                key === front_state_key ||
                key === back_state_key ||
                key === state.current_image_side ||
                key === rand_key + ".front_design" ||
                key === rand_key + ".back_design" ||
                key === rand_key + ".front_image" ||
                key === rand_key + ".back_image"
            ) {
                localStorage.removeItem(key);
            }
        });
    }
    handleDeleteOnKeyDown();
    handleDesignSave();
    handleAddToCart();
    handleImageSwapping();
    mouseDown();
    resizeObserve();

    canvas.on("object:modified", function (e) {
        save_side();
        save_state(state.current_image_url);
    });

    canvas.on("selection:created", function (e) {
        let canvas_defaults = getCanvasDefaults(canvas);
        const params = {
            ...canvas_defaults[product_type].box,
        };
        canvas.getObjects().forEach((obj) => {
            if (obj && obj.type == "rect") {
                obj.set({
                    ...params,
                    stroke: "#ccc",
                    strokeWidth: 2,
                });
                canvas.renderAll();
            }
        });
    });

    canvas.on("selection:cleared", function () {
        canvas.getObjects().forEach((obj) => {
            if (obj && obj.type == "rect") {
                obj.set({
                    strokeWidth: 0,
                    stroke: "",
                });
            }
        });
    });
}

function addInnerBorder() {
    let canvas_defaults = getCanvasDefaults(canvas);
    const params = {
        ...canvas_defaults[product_type].box,
    };
    let boundingBox = new fabric.Rect({
        ...params,
        strokeWidth: 0,
        hasControls: false,
        lockMovementX: true,
        lockMovementY: true,
        lockScalingX: true,
        lockScalingY: true,
        lockRotation: true,
    });
    canvas.add(boundingBox);

    let clipPath = new fabric.Rect({
        left: params.left,
        top: params.top,
        width: params.width,
        height: params.height,
        originX: "center",
        originY: "center",
        absolutePositioned: true,
        stay: params.stay,
        stay_when_pos: true,
        hasControls: false,
        lockMovementX: true,
        lockMovementY: true,
        lockScalingX: true,
        lockScalingY: true,
        lockRotation: true,
    });

    designGroup = new fabric.Group([], {
        left: 0,
        top: 0,
        clipPath: clipPath,
        selectable: false,
        evented: true,
        subTargetCheck: true,
        interactive: true,
        stay: params.stay,
        stay_when_pos: true,
        hasControls: false,
        lockMovementX: true,
        lockMovementY: true,
        lockScalingX: true,
        lockScalingY: true,
        lockRotation: true,
    });

    canvas.add(designGroup);
    canvas.designGroup = designGroup;

    originalAdd = canvas.add.bind(canvas);
    canvas.add = function (...objects) {
        objects.forEach((obj) => {
            if (obj !== boundingBox && !obj.excludeFromClipping) {
                obj.clipPath = clipPath;
                originalAdd(obj);
            } else {
                originalAdd(obj);
            }
        });
        canvas.renderAll();
        save_side();
        save_state(state.current_image_url);
        return canvas;
    };
}

function initForm() {
    handleInlineTextInputs(text_objects);
    handleFontFamilyInput(form.font_family);
    handleTextColorInput(form.text_color);
    handleFontSizeInput(form.font_size);
    handleTextStyleButtons(form.btns);
    setupDynamicTextInputs();
}

function setupDynamicTextInputs() {
    if (!form.add_text_btn || !form.text_container) {
        console.error("Add text button or text container not found");
        return;
    }

    form.add_text_btn.addEventListener("click", function () {
        const inputId = "text_" + Date.now();
        const newInputHTML = `
            <div class="text-input-group d-flex align-items-center gap-2" data-input-id="${inputId}">
                <div class="input-wrapper flex-grow-1">
                    <input type="text" id="${inputId}" class="form-control input-styled my-4" placeholder="შეიყვანე ტექსტი">
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-text-btn">✕</button>
            </div>

        `;

        form.text_container.insertAdjacentHTML("beforeend", newInputHTML);

        const newInput = document.getElementById(inputId);
        if (newInput) {
            handleTextInputs([newInput]);

            const removeBtn = newInput
                .closest(".text-input-group")
                .querySelector(".remove-text-btn");
            removeBtn.addEventListener("click", function () {
                if (text_objects[inputId]) {
                    canvas.remove(text_objects[inputId]);
                    delete text_objects[inputId];
                    canvas.renderAll();
                    save_side();
                    save_state(state.current_image_url);
                }
                newInput.closest(".text-input-group").remove();
            });
        }
    });
}

function mapTextObjectsToFormInputs() {
    Object.keys(text_objects).forEach((key) => {
        const input = document.getElementById(key);
        if (input) {
            input.value = text_objects[key].text;
        }
    });
}

function mouseDown() {
    canvas.on("mouse:down", function (options) {
        if (options.target) {
            if (options.target.clipPath) {
                canvas.setActiveObject(options.target);

                if (options.target.type === "textbox") {
                    active_text_obj = options.target;
                }
            }
            save_side();
            save_state(state.current_image_url);
        }
    });
}

function resizeObserve() {
    canvas.on("resize", function () {
        const newParams = {
            left: canvas.width / 2,
            top: canvas.height / 2,
            width: canvas.width * 0.4,
            height: canvas.height * 0.2,
        };

        clipPath.set(newParams);
        boundingBox.set(newParams);

        canvas.getObjects().forEach((obj) => {
            if (obj !== boundingBox && !obj.excludeFromClipping) {
                obj.clipPath = clipPath;
            }
        });

        canvas.renderAll();

        save_side();
        save_state(state.current_image_url);
    });
}

function handleImageSwapping() {
    let colorSwitcherBtns = document.querySelectorAll(".color-option");

    colorSwitcherBtns.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            color_chosen = true;
            selectedFrontImage = this.getAttribute("data-front-image");
            selectedBackImage = this.getAttribute("data-back-image");
            if (!selectedFrontImage.includes("color")) {
                selectedFrontImage = null;
            }

            if (!selectedBackImage.includes("color")) {
                selectedBackImage = null;
            }

            enableFormElements();
            if (selectedFrontImage) {
                loadImage(selectedFrontImage, "color", selectedBackImage);
            }
        });
    });

    let show_front_btn = document.querySelector("#showFront");

    if (show_front_btn) {
        show_front_btn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("clicked front");
            if (!selectedFrontImage) {
                return;
            }
            loadImage(selectedFrontImage, "pos");
        });
    }

    let show_back_btn = document.querySelector("#showBack");
    if (show_back_btn) {
        show_back_btn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("selectedBackImage: ", selectedBackImage);
            console.log("clicked back");
            if (!selectedBackImage) {
                return;
            }
            loadImage(selectedBackImage, "pos");
        });
    }
}

function enableFormElements() {
    const formElements = document.querySelectorAll(
        "#customizationForm input, #customizationForm select, #customizationForm button"
    );
    formElements.forEach((element) => {
        element.disabled = false;
    });
}

function showBorder(obj) {
    let canvas_defaults = getCanvasDefaults(canvas);
    const params = {
        ...canvas_defaults[product_type].box,
    };
    obj.set({
        ...params,
        stroke: default_stoke_fill,
        strokeWidth: default_stroke_width,
    });
}

function hideBorder(obj) {
    obj.set({
        stroke: "",
        strokeWidth: 0,
    });
}

function loadImage(
    imageURL,
    type = "color",
    backImageURL = "",
    first_time = false
) {
    if (!imageURL) return;

    if (state.current_image_url == imageURL) {
        return;
    }

    state.current_image_url = imageURL;

    console.log("imageURL: ", imageURL);
    console.log("backImageURL: ", backImageURL);

    if (type === "pos") {
        state.current_image_side == "front"
            ? (state.current_image_side = "back")
            : (state.current_image_side = "front");

        console.log("changing side to:  ", state.current_image_side);

        let key =
            state.current_image_side == "front"
                ? front_state_key
                : back_state_key;

        let obj_state = localStorage.getItem(key);
        if (!obj_state) {
            canvas.getObjects().forEach((obj) => {
                if (
                    !(
                        obj.stay_when_pos ||
                        obj.type === "rect" ||
                        obj.type === "group"
                    )
                ) {
                    canvas.remove(obj);
                }

                if (obj.type == "rect") {
                    hideBorder(obj);
                }
            });

            fabric.Image.fromURL(imageURL, function (img) {
                let scale = Math.min(
                    canvas.width / img.width,
                    canvas.height / img.height
                );

                img.set({
                    product_image: true,
                    left: canvas.width / 2,
                    top: canvas.height / 2,
                    originX: "center",
                    originY: "center",
                    scaleX: scale,
                    scaleY: scale,
                    selectable: false,
                    hasControls: false,
                    excludeFromClipping: true,
                });

                canvas.add(img);
                canvas.sendToBack(img);
                canvas.renderAll();

                localStorage.setItem(imageURL, JSON.stringify(canvas));
            });
        } else {
            canvas.clear();

            if (form.text_container) {
                Array.from(form.text_container.children).forEach((child) => {
                    if (child.id !== "addTextInput") {
                        child.remove();
                    }
                });
            }

            canvas.loadFromJSON(obj_state, function () {
                canvas.renderAll();
                text_objects = {};

                canvas.getObjects().forEach((obj) => {
                    if (obj.type == "rect") {
                        obj.set({
                            stay: true,
                            stay_when_pos: true,
                            hasControls: false,
                            selectable: false,
                            lockMovementX: true,
                            lockMovementY: true,
                            lockScalingX: true,
                            lockScalingY: true,
                            lockRotation: true,
                        });

                        hideBorder(obj);
                    }

                    if (
                        obj._originalElement &&
                        obj._originalElement.src.includes("color")
                    ) {
                        obj.set({
                            selectable: false,
                            hasControls: false,
                            excludeFromClipping: true,
                            product_image: true,
                        });

                        canvas.sendToBack(obj);
                    }

                    if (
                        obj.type == "image" &&
                        obj._originalElement &&
                        obj._originalElement.src.includes("clipart")
                    ) {
                        obj.set({
                            selectable: true,
                            hasControls: true,
                            excludeFromClipping: false,
                        });
                    }

                    if (obj.type === "textbox") {
                        if (obj.input_id) {
                            text_objects[obj.input_id] = obj;
                            createDynamicTextInput(obj.input_id, obj.text);
                        } else {
                            const inputId = "text_" + Date.now();
                            obj.input_id = inputId;
                            text_objects[inputId] = obj;
                            createDynamicTextInput(inputId, obj.text);
                        }
                    }
                });

                const dynamicInputs = document.querySelectorAll(
                    ".dynamic-text-input"
                );
                if (dynamicInputs.length > 0) {
                    handleTextInputs(Array.from(dynamicInputs));
                }
                handleInlineTextInputs(text_objects);
                mapTextObjectsToFormInputs();
            });

            fabric.Image.fromURL(imageURL, function (img) {
                let scale = Math.min(
                    canvas.width / img.width,
                    canvas.height / img.height
                );

                img.set({
                    product_image: true,
                    left: canvas.width / 2,
                    top: canvas.height / 2,
                    originX: "center",
                    originY: "center",
                    scaleX: scale,
                    scaleY: scale,
                    selectable: false,
                    hasControls: false,
                    excludeFromClipping: true,
                });

                canvas.add(img);
                canvas.sendToBack(img);
                canvas.renderAll();
            });
        }

        return;
    }

    if (type == "color") {
        if (first_time) {
            let key =
                state.current_image_side == "front"
                    ? front_state_key
                    : back_state_key;
            let obj_state = localStorage.getItem(key);

            if (obj_state) {
                canvas.loadFromJSON(obj_state);
            }

            fabric.Image.fromURL(imageURL, function (img) {
                let scale = Math.min(
                    canvas.width / img.width,
                    canvas.height / img.height
                );

                img.set({
                    product_image: true,
                    left: canvas.width / 2,
                    top: canvas.height / 2,
                    originX: "center",
                    originY: "center",
                    scaleX: scale,
                    scaleY: scale,
                    selectable: false,
                    hasControls: false,
                    excludeFromClipping: true,
                });

                canvas.add(img);
                canvas.sendToBack(img);
                canvas.renderAll();
            });
            return;
        }

        swapColor(imageURL, backImageURL);
        return;
    }
}

function swapColor(imageURL, backImageURL) {
    removeImage();

    let url = state.current_image_side == "front" ? imageURL : backImageURL;
    state.current_image_url = url;
    fabric.Image.fromURL(url, function (img) {
        let scale = Math.min(
            canvas.width / img.width,
            canvas.height / img.height
        );

        img.set({
            product_image: true,
            left: canvas.width / 2,
            top: canvas.height / 2,
            originX: "center",
            originY: "center",
            scaleX: scale,
            scaleY: scale,
            selectable: false,
            hasControls: false,
            excludeFromClipping: true,
        });

        canvas.add(img);
        canvas.sendToBack(img);
        canvas.renderAll();
    });
}

function handleDeleteOnKeyDown() {
    document.addEventListener("keydown", function (e) {
        if (e.key === "Delete") {
            let active = canvas.getActiveObject();
            if (active) {
                if (active_text_obj === active) {
                    active_text_obj = null;
                    delete text_objects[active.input_id];
                    if (form[active.input_id]) {
                        form[active.input_id].value = "";
                    }
                }
                canvas.remove(active);
                canvas.renderAll();

                save_side();
                save_state(state.current_image_url);
            }
        }
    });
}

function removeImage() {
    canvas.getObjects().forEach((obj) => {
        if (obj.product_image) {
            canvas.remove(obj);
        }
    });
}

// _______________________________________________________________________
function handleTextStyleButtons(buttons) {
    buttons.forEach((btn) => {
        btn.addEventListener("click", () => {
            if (!active_text_obj) {
                alert("Please select a text object first");
                return;
            }

            const style = btn.getAttribute("data-style");

            const actions = {
                bold: () => toggleStyle("fontWeight", "bold", "normal"),
                italic: () => toggleStyle("fontStyle", "italic", "normal"),
                underline: () => toggleStyle("underline", true, false),
                shadow: () =>
                    toggleStyle("shadow", "2px 2px 5px rgba(0,0,0,0.3)", ""),
                curved: () => applyCurvedTextEffect(active_text_obj),
                normal: () =>
                    active_text_obj.set({
                        fontWeight: "normal",
                        fontStyle: "normal",
                        underline: false,
                        shadow: "",
                        path: null,
                    }),
            };

            if (actions[style]) actions[style]();
            canvas.renderAll();
        });
    });
}

function toggleStyle(property, value1, value2) {
    active_text_obj.set(
        property,
        active_text_obj[property] === value1 ? value2 : value1
    );
}

function applyCurvedTextEffect(obj) {
    if (!obj || obj.type !== "textbox") {
        alert("Please select a text object.");
        return;
    }

    let text = obj.text || " ";
    let radius = 80;
    let spacing = Math.max(5, 150 / text.length);

    obj.set("path", null);

    let path = new fabric.Path(
        `M 0,${radius} A ${radius},${radius / 1.5} 0 1,1 ${
            radius * 2
        },${radius}`,
        {
            fill: "",
            stroke: "",
            selectable: false,
            evented: false,
        }
    );

    obj.set({
        path: path,
        pathSide: "top",
        pathAlign: "center",
        charSpacing: spacing * 10,
        originX: "center",
        left: canvas.width / 2,
    });

    canvas.renderAll();
    save_side();
    save_state(state.current_image_url);
}

function handleFontSizeInput(input) {
    input.addEventListener("input", (e) => {
        if (active_text_obj) {
            active_text_obj.set("fontSize", parseInt(input.value));
            canvas.renderAll();
            save_side();
            save_state(state.current_image_url);
        }
    });
}

function handleTextColorInput(input) {
    input.addEventListener("click", (e) => {
        input.showPicker();
    });

    input.addEventListener("change", (e) => {
        if (active_text_obj) {
            active_text_obj.set("fill", input.value);
            canvas.renderAll();

            save_side();
            save_state(state.current_image_url);
        }
    });
}

function handleFontFamilyInput(input) {
    input.addEventListener("change", (e) => {
        if (active_text_obj) {
            active_text_obj.set("fontFamily", input.value);
            canvas.renderAll();

            save_side();
            save_state(state.current_image_url);
        }
    });
}

function handleTextInputs(inputs) {
    let canvas_defaults = getCanvasDefaults(canvas);

    for (let input of inputs) {
        if (!input) continue;

        input.addEventListener("input", (e) => {
            if (text_objects[input.id]) {
                text_objects[input.id].set({ text: input.value });
                canvas.setActiveObject(text_objects[input.id]);
                active_text_obj = text_objects[input.id];
                canvas.renderAll();
                localStorage.setItem(
                    state.current_image_url,
                    JSON.stringify(canvas)
                );
            } else {
                // Create a new text object - use a better positioning algorithm
                const numExistingTexts = Object.keys(text_objects).length;
                const clipHeight = canvas.height * 0.2;
                const clipTop = canvas.height / 2 - clipHeight / 2;

                // Position text evenly within the design area
                const index = numExistingTexts % 5; // Cycle through 5 possible positions
                const yPosition = clipTop + (clipHeight * (index + 1)) / 6;

                // Default text properties
                const textDefaults = canvas_defaults[input.id] || {
                    fontSize: 20,
                    fontFamily: "Arial",
                    fill: "#000000",
                    textAlign: "center",
                };

                text_objects[input.id] = new fabric.Textbox("", {
                    left: canvas.width / 2,
                    input_id: input.id,
                    top: yPosition,
                    originX: "center",
                    originY: "center",
                    textAlign: "center",
                    selectable: true,
                    evented: true,
                    ...textDefaults,
                });

                canvas.add(text_objects[input.id]);
                text_objects[input.id].set({ text: input.value });
                canvas.setActiveObject(text_objects[input.id]);
                active_text_obj = text_objects[input.id];
                canvas.renderAll();
                localStorage.setItem(
                    state.current_image_url,
                    JSON.stringify(canvas)
                );
            }
        });
    }
}

function handleInlineTextInputs(objects) {
    canvas.on("text:changed", (e) => {
        let obj = e.target;

        if (obj.input_id) {
            const input = document.getElementById(obj.input_id);
            if (input) {
                input.value = obj.text;
            }
        }

        save_state(state.current_image_url);
    });
}

function createDynamicTextInput(inputId, text) {
    if (!form.text_container) return;

    const newInputHTML = `
        <div class="text-input-group d-flex align-items-center gap-2" data-input-id="${inputId}">
            <div class="input-wrapper flex-grow-1">
                <input type="text" id="${inputId}" class="form-control input-styled my-4" placeholder="შეიყვანე ტექსტი" value="${
        text || ""
    }">
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-text-btn">✕</button>
        </div>
    `;

    form.text_container.insertAdjacentHTML("beforeend", newInputHTML);

    const newInput = document.getElementById(inputId);
    if (newInput) {
        // Setup remove button for this input
        const removeBtn = newInput
            .closest(".text-input-group")
            .querySelector(".remove-text-btn");
        removeBtn.addEventListener("click", function () {
            if (text_objects[inputId]) {
                canvas.remove(text_objects[inputId]);
                delete text_objects[inputId];
                canvas.renderAll();
                save_side();
                save_state(state.current_image_url);
            }
            newInput.closest(".text-input-group").remove();
        });
    }
}

function initProductImage() {
    let color_btns = document.querySelectorAll(".color-option");
    let first_color = color_btns[0];
    let first_front_image = first_color.getAttribute("data-front-image");

    let key = front_state_key;

    let canvas_state = localStorage.getItem(key);

    if (canvas_state) {
        loadImage(first_front_image, "color", "", true);

        selectedFrontImage = first_front_image;
        if (!selectedFrontImage.includes("color")) {
            selectedFrontImage = null;
        }

        selectedBackImage = first_color.getAttribute("data-back-image");

        if (!selectedBackImage.includes("color")) {
            selectedBackImage = null;
        }
    } else {
        selectedFrontImage = first_front_image;
        if (!selectedFrontImage.includes("color")) {
            selectedFrontImage = null;
        }
        selectedBackImage = first_color.getAttribute("data-back-image");
        if (!selectedBackImage.includes("color")) {
            selectedBackImage = null;
        }
        state.front_image_url = first_front_image;
        state.back_image_url = selectedBackImage;
        loadImage(first_front_image, "color", selectedBackImage);
    }
    enableFormElements();
}

function loadImageFirstTime(imageURL) {}

function sidebarHandler() {
    clipArtHandler();
    uploadHandler();
}

function clipArtHandler() {
    document.querySelectorAll(".clipart-img").forEach((img) => {
        img.addEventListener("click", addClipArtToCanvas);
    });

    let cat_dropdown = document.querySelector("#clipartCategory");
    cat_dropdown.addEventListener("change", switchClipArtCats);
}

function uploadHandler() {
    const clipart_upload_sidebar = document.querySelector("#uploadSidebar");
    const clipart_upload_btn = document.querySelector("#toggleUploadSidebar");
    const close_clipart_sidebar = document.querySelector("#closeUploadSidebar");
    let uploaded_image = document.querySelector("#uploaded_image");

    clipart_upload_btn.addEventListener("click", (e) =>
        toggleSideBar(e, clipart_upload_sidebar)
    );

    close_clipart_sidebar.addEventListener("click", (e) => {
        toggleSideBar(e, clipart_upload_sidebar, 0);
    });

    uploaded_image.addEventListener("change", function (e) {
        let reader = new FileReader();
        reader.onload = function () {
            let img = document.createElement("img");
            img.src = reader.result;
            document.querySelector("#imagePreviewContainer").innerHTML = "";
            document.querySelector("#imagePreviewContainer").appendChild(img);
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    if (document.querySelector("#imagePreviewContainer")) {
        document
            .querySelector("#imagePreviewContainer")
            .addEventListener("click", function (e) {
                let imgElement = e.target;
                let imgSrc = imgElement.src;
                if (!imgSrc) return;

                fabric.Image.fromURL(imgSrc, function (img) {
                    let max_w = canvas.width * 0.4;
                    let max_h = canvas.height * 0.4;

                    let scale = Math.min(max_w / img.width, max_h / img.height);

                    img.set({
                        left: canvas.width / 2 - (img.width * scale) / 2,
                        top: canvas.height / 2 - (img.height * scale) / 2,
                        scaleX: scale,
                        scaleY: scale,
                        selectable: true,
                    });

                    canvas.add(img);
                    canvas.setActiveObject(img);

                    save_side();
                    save_state(state.current_image_url);
                });
            });
    }
}

function addClipArtToCanvas() {
    let url = this.getAttribute("data-image");

    fabric.Image.fromURL(url, function (img) {
        let max_w = canvas.width * 0.4;
        let max_h = canvas.height * 0.4;

        let scale = Math.min(max_w / img.width, max_h / img.height);

        img.set({
            left: canvas.width / 2 - (img.width * scale) / 2,
            top: canvas.height / 2 - (img.height * scale) / 2,
            scaleX: scale,
            scaleY: scale,
            selectable: true,
            hasControls: true,
            stay: true,
        });

        canvas.add(img);
        canvas.setActiveObject(img);
        canvas.renderAll();
        save_state(state.current_image_url);
    });
}

function switchClipArtCats(e) {
    let selected_cat = e.target.value;
    let cliparts = document.querySelectorAll(".clipart-img");

    cliparts.forEach((img) => {
        if (selected_cat === "all" || img.dataset.category === selected_cat) {
            img.style.display = "block";
        } else {
            img.style.display = "none";
        }
    });
}

function resizeCanvas(defaulting) {
    if (defaulting) {
        canvas.setWidth(designArea.clientWidth);
        canvas.setHeight(designArea.clientWidth * 1.5);
    }
}

function save_side() {
    let key =
        state.current_image_side == "front" ? front_state_key : back_state_key;

    let canvasData = canvas.toJSON();

    canvasData.objects = canvasData.objects.filter(
        (obj) => !obj.src || !obj.src.includes("color")
    );

    localStorage.setItem(key, JSON.stringify(canvasData));
}

function save_state(image_url) {
    localStorage.setItem(image_url, JSON.stringify(canvas));
}

let final_design = {
    front_image: "",
    back_image: "",
};

function handleDesignSave() {
    document
        .querySelector("#saveDesign")
        .addEventListener("click", function (e) {
            e.preventDefault();

            const currentSide = state.current_image_side;
            saveDesignAndImage(currentSide);

            if (selectedBackImage) {
                if (currentSide === "front" && selectedBackImage) {
                    loadImage(selectedBackImage, "pos");

                    setTimeout(() => {
                        saveDesignAndImage("back");
                        showButtons();
                        alert("Item design successfully saved");
                    }, 500);
                } else if (currentSide === "back" && selectedFrontImage) {
                    loadImage(selectedFrontImage, "pos");

                    setTimeout(() => {
                        saveDesignAndImage("front");
                        showButtons();
                        alert("Item design successfully saved");
                    }, 500);
                }
            } else {
                showButtons();
                alert("Item design successfully saved");
            }
        });
}

function saveDesignAndImage(side) {
    try {
        localStorage.setItem(
            `${rand_key}.${side}_design`,
            JSON.stringify(canvas)
        );

        const stateKey = side === "front" ? front_state_key : back_state_key;
        localStorage.setItem(stateKey, JSON.stringify(canvas.toJSON()));

        const removed_objects = [];
        canvas.getObjects().forEach((obj) => {
            if (obj.type === "rect" || obj.type === "group") {
                removed_objects.push(obj);
                canvas.remove(obj);
            }
        });

        const imageData = canvas.toDataURL({
            format: "png",
            quality: 1,
        });

        removed_objects.forEach((obj) => {
            obj.set({
                selectable: false,
                hasControls: false,
                evented: false,
                stay: true,
                stay_when_pos: true,
            });

            originalAdd(obj);
            canvas.renderAll();
        });

        if (side === "front") {
            final_design.front_image = imageData;
        } else {
            final_design.back_image = imageData;
        }
    } catch (err) {
        alert(
            "Unable to save design. Storage limit reached. Try clearing browser data or removing old designs."
        );
    }
}

function showButtons() {
    const add_to_cart_btn = document.querySelector("#addToCart");
    add_to_cart_btn.style.display = "block";
}

function handleAddToCart() {
    document
        .querySelector("#addToCart")
        .addEventListener("click", function (e) {
            e.preventDefault();

            if (!final_design.front_image) {
                alert("Please save your design first");
                return;
            }

            const backImage = final_design.back_image || null;

            let form = {
                front_image: final_design.front_image,
                back_image: backImage,
                product_id: product_image.getAttribute("data-id"),
                v_hash: localStorage.getItem("v_hash"),
                quantity: localStorage.getItem("quantity") || 1,
                price: null,
                default_img: 0,
            };

            let formData = new FormData();
            formData.append("front_image", form.front_image);
            formData.append("product_id", form.product_id);
            formData.append("v_hash", form.v_hash);
            formData.append("quantity", form.quantity);
            formData.append("default_img", form.default_img);

            if (backImage) {
                formData.append("back_image", backImage);
            }

            axios
                .post("/cart", formData)
                .then((response) => {
                    alert("Item successfully added to cart");
                })
                .catch((error) => {
                    console.error("Error adding to cart:", error);
                    alert("There was an error adding the item to cart");
                });
        });
}
