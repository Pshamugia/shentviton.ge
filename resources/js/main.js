import toggleSideBar from "./utils";
import getCanvasDefaults from "./defaults.js";
const sidebar = document.querySelector("#clipartSidebar");
const toggle_btn = document.querySelector("#toggleClipartSidebar");
const close_btn = document.querySelector("#closeClipartSidebar");
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
    top_text: document.querySelector("#top_text"),
    bottom_text: document.querySelector("#bottom_text"),
    font_family: document.querySelector("#font_family"),
    font_size: document.querySelector("#font_size"),
    text_color: document.querySelector("#text_color"),
    btns: cbtns,
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
    handleTextInputs([form.top_text, form.bottom_text]);
    handleInlineTextInputs(text_objects);
    handleFontFamilyInput(form.font_family);
    handleTextColorInput(form.text_color);
    handleFontSizeInput(form.font_size);
    handleTextStyleButtons(form.btns);
}

//****_________________________________________________________________________________________****//

function mapTextObjectsToFormInputs() {
    Object.keys(text_objects).forEach((key) => {
        if (form[key]) {
            form[key].value = text_objects[key].text;
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
            enableFormElements();
            loadImage(selectedFrontImage, "color", selectedBackImage);
        });
    });

    document.querySelector("#showFront").addEventListener("click", function () {

        console.log("clicked front");
        loadImage(selectedFrontImage, "pos");
    });

    document.querySelector("#showBack").addEventListener("click", function () {
        console.log("clicked back");
        loadImage(selectedBackImage, "pos");
    });
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
                        if (obj.top < canvas.height / 2) {
                            obj.input_id = "top_text";
                            text_objects["top_text"] = obj;
                            if (
                                form.top_text instanceof HTMLElement &&
                                "value" in form.top_text
                            ) {
                                form.top_text.value = obj.text;
                            } else {
                            }
                        } else {
                            obj.input_id = "bottom_text";
                            text_objects["bottom_text"] = obj;

                            if (
                                form.bottom_text instanceof HTMLElement &&
                                "value" in form.bottom_text
                            ) {
                                form.bottom_text.value = obj.text;
                            } else {
                            }
                        }
                    }
                });

                handleTextInputs([form.top_text, form.bottom_text]);
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

    if (inputs.length < 2) {
        let missing = inputs[0].id === "top_text" ? "bottom_text" : "top_text";

        let clipHeight = canvas.height * 0.2;
        let clipTop = canvas.height / 2 - clipHeight / 2;
        text_objects[missing] = new fabric.Textbox("", {
            left: canvas.width / 2,
            input_id: missing,
            top:
                clipTop +
                (missing === "top_text"
                    ? clipHeight * 0.25
                    : clipHeight * 0.75),
            originX: "center",
            originY: "center",
            textAlign: "center",
            selectable: true,
            evented: true,
            ...canvas_defaults[missing],
        });

        text_objects[missing].set({ text: "" });
        canvas.add(text_objects[missing]);
        canvas.setActiveObject(text_objects[missing]);
        canvas.renderAll();
        localStorage.setItem(state.current_image_url, JSON.stringify(canvas));
    }

    for (let input of inputs) {
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
                const clipHeight = canvas.height * 0.2;
                const clipTop = canvas.height / 2 - clipHeight / 2;
                text_objects[input.id] = new fabric.Textbox("", {
                    left: canvas.width / 2,
                    input_id: input.id,
                    top:
                        clipTop +
                        (input.id === "top_text"
                            ? clipHeight * 0.25
                            : clipHeight * 0.75),
                    originX: "center",
                    originY: "center",
                    textAlign: "center",
                    selectable: true,
                    evented: true,
                    ...canvas_defaults[input.id],
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

        let key = Object.keys(text_objects).find(
            (k) => text_objects[k] === obj
        );
        if (key && form[key]) {
            form[key].value = obj.text;
        }

        // save_side();
        save_state(state.current_image_url);
    });
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
        selectedBackImage = first_color.getAttribute("data-back-image");
    } else {
        selectedFrontImage = first_front_image;
        selectedBackImage = first_color.getAttribute("data-back-image");
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
        // save_side();
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

function handleDesignSave() {
    document
        .querySelector("#saveDesign")
        .addEventListener("click", function (e) {
            e.preventDefault();

            if (!state.front_image_url || !state.back_image_url) {
                return;
            }

            const is_front = state.front_image_url === state.current_image_url;
            const first_side = is_front ? "front" : "back";
            const second_side = is_front ? "back" : "front";
            const second_side_url = is_front
                ? state.back_image_url
                : state.front_image_url;

            saveDesignAndImage(first_side, rand_key);

            loadImage(second_side_url, "pos");

            setTimeout(() => {
                saveDesignAndImage(second_side, rand_key);
                showButtons(rand_key);
                alert("Item design successfully saved");
            }, 500);
        });
}

function saveDesignAndImage(side, rand_key) {
    try {
        localStorage.setItem(
            `${rand_key}.${side}_design`,
            JSON.stringify(canvas)
        );

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

        try {
            localStorage.setItem(`${rand_key}.${side}_image`, imageData);
        } catch (err) {
            if (err.name === "QuotaExceededError") {
                clearOldDesigns(rand_key);
                localStorage.setItem(`${rand_key}.${side}_image`, imageData);
            } else {
                throw err;
            }
        }
    } catch (err) {
        alert(
            "Unable to save design. Storage limit reached. Try clearing browser data or removing old designs."
        );
    }
}

function clearOldDesigns(currentKey) {
    const keyPrefix = currentKey.split(".")[0];
    const keysToRemove = [];

    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key.startsWith(keyPrefix) && !key.startsWith(currentKey)) {
            keysToRemove.push(key);
        }
    }

    keysToRemove.forEach((key) => {
        localStorage.removeItem(key);
    });
}

function showButtons(rand_key) {
    const add_to_cart_btn = document.querySelector("#addToCart");
    add_to_cart_btn.style.display = "block";
}
function handleAddToCart() {
    document
        .querySelector("#addToCart")
        .addEventListener("click", function (e) {
            e.preventDefault();

            if (!state.front_image_url || !state.back_image_url) {
                return;
            }

            let form = {
                front_image: localStorage.getItem(rand_key + ".front_image"),
                back_image: localStorage.getItem(rand_key + ".back_image"),
                product_id: document
                    .querySelector("#product-image")
                    .getAttribute("data-id"),
                v_hash: localStorage.getItem("v_hash"),
                quantity: localStorage.getItem("quantity") || 1,

                price: null,
                default_img: 0,
            };

            let formData = new FormData();
            formData.append("front_image", form.front_image);
            formData.append("back_image", form.back_image);
            formData.append("product_id", form.product_id);
            formData.append("v_hash", form.v_hash);
            formData.append("quantity", form.quantity);
            formData.append("default_img", form.default_img);

            axios
                .post("/cart", formData)
                .then((response) => {
                    alert("Item successfully added to cart");
                })
                .catch((error) => {});
        });
}
