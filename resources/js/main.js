import toggleSideBar from "./utils";
import getCanvasDefaults from "./defaults.js";
// const canvasContainer = document.querySelector("#canvasContainer");
const designArea = document.querySelector("#design-area");
const canvas = new fabric.Canvas("tshirtCanvas");
const product_image = document.querySelector("#product-image");

// canvas.setHeight(canvasContainer.clientHeight);
// canvas.setWidth(canvasContainer.clientWidth);
// canvas.requestRenderAll();

const product_type = product_image?.getAttribute("data-type") || "default";
const default_stroke_width = 2;
const default_stoke_fill = "#ccc";
const product_id = product_image?.getAttribute("data-id");
const front_state_key = `${product_id}.front_design`;
const back_state_key = `${product_id}.back_design`;

const rand_key = Math.random().toString(36).substring(7);

const emitAddedToCanvas = function () {
    const event = new CustomEvent("addedToCanvas");
    document.dispatchEvent(event);
};

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

let zoomLevel = 1;
const zoomStep = 0.1;

fabric.Object.prototype.controls.deleteControl = new fabric.Control({
    x: 0.5,
    y: -0.5,
    offsetY: 0,
    offsetX: 0,
    cursorStyle: "pointer",
    mouseUpHandler: function (eventData, transform) {
        const target = transform.target;
        const canvas = target.canvas;
        const input = target ? document.getElementById(target.input_id) : null;
        const remove_btn = input
            ? input
                  .closest(".text-input-group")
                  .querySelector(".remove-text-btn")
            : null;

        if (
            remove_btn &&
            input &&
            input.id == target.input_id &&
            target.type === "textbox"
        ) {
            input.remove();
            remove_btn.remove();
            canvas.remove(target);
            canvas.requestRenderAll();
            save_side();
            save_state(state.current_image_url);
            return;
        }

        canvas.remove(target);
        canvas.requestRenderAll();
        save_side();
        save_state(state.current_image_url);
    },
    render: function (ctx, left, top, styleOverride, fabricObject) {
        const size = this.sizeX || 24;
        ctx.save();

        ctx.fillStyle = "red";
        ctx.beginPath();
        ctx.arc(left, top, size / 2, 0, Math.PI * 2, false);
        ctx.fill();

        ctx.strokeStyle = "white";
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(left - 5, top - 5);
        ctx.lineTo(left + 5, top + 5);
        ctx.moveTo(left + 5, top - 5);
        ctx.lineTo(left - 5, top + 5);
        ctx.stroke();

        ctx.restore();
    },
    sizeX: 20,
    sizeY: 20,
});

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
    document.getElementById("zoom-in").addEventListener("click", () => {
        if (zoomLevel < 2) {
            zoomLevel += zoomStep;
            setCanvasZoom(zoomLevel);
        }
    });

    document.getElementById("zoom-out").addEventListener("click", () => {
        if (zoomLevel > 0.5) {
            zoomLevel -= zoomStep;
            setCanvasZoom(zoomLevel);
        }
    });

    function setCanvasZoom(zoom) {
        const center = canvas.getCenter();
        canvas.zoomToPoint(new fabric.Point(center.left, center.top), zoom);
        updateZoomDisplay();
    }

    function updateZoomDisplay() {
        document.getElementById("zoom-level").textContent =
            Math.round(zoomLevel * 100) + "%";
    }
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
                canvas.requestRenderAll();
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
        canvas.requestRenderAll();
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
                    <input type="text" id="${inputId}" class="form-control input-styled my-4 dynamic-text-input" placeholder="შეიყვანე ტექსტი">
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-text-btn">✕</button>
            </div>

        `;

        form.text_container.insertAdjacentHTML("beforeend", newInputHTML);

        const newInput = document.getElementById(inputId);
        if (newInput) {
            handleTextInputs([newInput]);

            const remove_btn = newInput
                .closest(".text-input-group")
                .querySelector(".remove-text-btn");
            remove_btn.addEventListener("click", function () {
                if (text_objects[inputId]) {
                    canvas.remove(text_objects[inputId]);
                    delete text_objects[inputId];
                    canvas.requestRenderAll();
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

        canvas.requestRenderAll();

        save_side();
        save_state(state.current_image_url);
    });
}

function handleImageSwapping() {
    let colorSwitcherBtns = document.querySelectorAll(".color-option");

    colorSwitcherBtns.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            color_chosen = true;
            document
                .querySelectorAll(".color-option")
                .forEach((b) => b.classList.remove("selected"));
            btn.classList.add("selected");
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
            // console.log("clicked front");
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
            // console.log("selectedBackImage: ", selectedBackImage);
            // console.log("clicked back");
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

    // console.log("imageURL: ", imageURL);
    // console.log("backImageURL: ", backImageURL);

    if (type === "pos") {
        state.current_image_side == "front"
            ? (state.current_image_side = "back")
            : (state.current_image_side = "front");

        // console.log("changing side to:  ", state.current_image_side);

        let key =
            state.current_image_side == "front"
                ? front_state_key
                : back_state_key;

        let obj_state = localStorage.getItem(key);
        if (!obj_state) {
            Array.from(form.text_container.children).forEach((child) => {
                if (child.id !== "addTextInput") {
                    child.remove();
                }
            });
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
                canvas.requestRenderAll();

                localStorage.setItem(imageURL, JSON.stringify(canvas));
            });
        } else {
            canvas.clear();

            if (form.text_container) {
                // console.log("here??");
                Array.from(form.text_container.children).forEach((child) => {
                    if (child.id !== "addTextInput") {
                        child.remove();
                    }
                });
            }

            let dynamicTextCounter = 0;

            canvas.loadFromJSON(obj_state, function () {
                canvas.requestRenderAll();
                text_objects = {};

                if (form.text_container) {
                    form.text_container.innerHTML = "";
                }

                canvas.getObjects().forEach((obj) => {
                    if (obj.type == "rect") {
                    }
                    if (
                        obj._originalElement &&
                        obj._originalElement.src.includes("color")
                    ) {
                    }
                    if (
                        obj.type == "image" &&
                        obj._originalElement &&
                        obj._originalElement.src.includes("clipart")
                    ) {
                    }
                    if (obj.type === "textbox") {
                        if (!obj.input_id) {
                            dynamicTextCounter++;
                            obj.input_id = "text_" + dynamicTextCounter;
                        }
                        text_objects[obj.input_id] = obj;

                        text_objects[obj.input_id].controls = {
                            ...fabric.Object.prototype.controls,
                            deleteControl:
                                fabric.Object.prototype.controls.deleteControl,
                        };

                        let existingInput = document.getElementById(
                            obj.input_id
                        );

                        // console.log("existingInput: ", existingInput);
                        if (existingInput) {
                            existingInput.value = obj.text;
                        } else {
                            createDynamicTextInput(obj.input_id, obj.text);
                        }
                    }
                });

                const dynamicInputs = document.querySelectorAll(
                    ".dynamic-text-input"
                );

                // console.log("dynamicInputs: ", dynamicInputs);
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
                canvas.requestRenderAll();
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
                canvas.requestRenderAll();
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
        canvas.requestRenderAll();
    });
}

function handleDeleteOnKeyDown() {
    document.addEventListener("keydown", function (e) {
        if (e.key === "Delete") {
            let active = canvas.getActiveObject();
            if (active && active.type === "textbox") {
                const input = document.getElementById(active.input_id);
                const remove_btn = input
                    .closest(".text-input-group")
                    .querySelector(".remove-text-btn");

                if (remove_btn && input && input.id == active.input_id) {
                    input.remove();
                    remove_btn.remove();
                }
                canvas.remove(active);
                canvas.requestRenderAll();

                save_side();
                save_state(state.current_image_url);
            } else if (active) {
                canvas.remove(active);
                canvas.requestRenderAll();
                save_side();
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
            canvas.requestRenderAll();

            emitAddedToCanvas();
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

    canvas.requestRenderAll();
    save_side();
    save_state(state.current_image_url);
    emitAddedToCanvas();
}

function handleFontSizeInput(input) {
    input.addEventListener("input", (e) => {
        if (active_text_obj) {
            active_text_obj.set("fontSize", parseInt(input.value));
            canvas.requestRenderAll();
            save_side();
            save_state(state.current_image_url);
            emitAddedToCanvas;
        } else {
            alert("Please select a text object first");
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
            canvas.requestRenderAll();

            save_side();
            save_state(state.current_image_url);
            emitAddedToCanvas();
        } else {
            alert("Please select a text object first");
        }
    });
}

function handleFontFamilyInput(input) {
    input.addEventListener("change", (e) => {
        if (active_text_obj) {
            active_text_obj.set("fontFamily", input.value);
            canvas.requestRenderAll();
            save_side();
            save_state(state.current_image_url);
            emitAddedToCanvas();
        } else {
            alert("Please select a text object first");
        }
    });
}

function handleTextInputs(inputs) {
    // console.log("inputs in handleTextInputs: ", inputs);
    let canvas_defaults = getCanvasDefaults(canvas);

    for (let input of inputs) {
        if (!input) continue;

        input.addEventListener("input", (e) => {
            if (text_objects[input.id]) {
                text_objects[input.id].set({ text: input.value });
                canvas.setActiveObject(text_objects[input.id]);
                active_text_obj = text_objects[input.id];
                canvas.requestRenderAll();
                save_side();
            } else {
                const numExistingTexts = Object.keys(text_objects).length;
                const clipHeight = canvas.height * 0.2;
                const clipTop = canvas.height / 2 - clipHeight / 2;

                const index = numExistingTexts % 5;
                const yPosition = clipTop + (clipHeight * (index + 1)) / 6;

                const textDefaults = canvas_defaults["text"] || {
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

                text_objects[input.id].controls = {
                    ...fabric.Object.prototype.controls,
                    deleteControl:
                        fabric.Object.prototype.controls.deleteControl,
                };

                canvas.add(text_objects[input.id]);
                text_objects[input.id].set({ text: input.value });
                canvas.setActiveObject(text_objects[input.id]);
                active_text_obj = text_objects[input.id];
                canvas.requestRenderAll();
                save_side();
            }
        });
    }
}

function handleInlineTextInputs(objects) {
    // console.log("objects in handleInlineTextInputs: ", objects);
    canvas.on("text:changed", (e) => {
        let obj = e.target;

        if (obj.input_id) {
            const input = document.getElementById(obj.input_id);
            // console.log("input: ", input);
            if (input) {
                input.value = obj.text;
            }
        }

        save_state(state.current_image_url);
        save_side();
    });
}

function createDynamicTextInput(inputId, text) {
    if (!form.text_container) return;

    const newInputHTML = `
        <div class="text-input-group d-flex align-items-center gap-2" data-input-id="${inputId}">
            <div class="input-wrapper flex-grow-1">
                <input type="text" id="${inputId}" class="form-control input-styled my-4 dynamic-text-input" placeholder="შეიყვანე ტექსტი" value="${
        text || ""
    }">
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-text-btn">✕</button>
        </div>
    `;

    form.text_container.insertAdjacentHTML("beforeend", newInputHTML);

    const newInput = document.getElementById(inputId);
    if (newInput) {
        const remove_btn = newInput
            .closest(".text-input-group")
            .querySelector(".remove-text-btn");
        remove_btn.addEventListener("click", function () {
            if (text_objects[inputId]) {
                canvas.remove(text_objects[inputId]);
                delete text_objects[inputId];
                canvas.requestRenderAll();
                save_side();
                save_state(state.current_image_url);
            }
            newInput.closest(".text-input-group").remove();
        });
    }
}

function initProductImage() {
    // showLoadingIndicator();

    const color_btns = document.querySelectorAll(".color-option");
    const first_color = color_btns[0];
    const first_front_image = first_color.getAttribute("data-front-image");
    const first_back_image = first_color.getAttribute("data-back-image");

    selectedFrontImage = first_front_image.includes("color")
        ? first_front_image
        : null;
    selectedBackImage = first_back_image.includes("color")
        ? first_back_image
        : null;

    state.front_image_url = first_front_image;
    state.back_image_url = selectedBackImage;

    preloadImage(first_front_image).then(() => {
        const key = front_state_key;
        const canvas_state = localStorage.getItem(key);

        if (canvas_state) {
            requestAnimationFrame(() => {
                loadImage(first_front_image, "color", "", true);
                // hideLoadingIndicator();
                enableFormElements();
            });
        } else {
            requestAnimationFrame(() => {
                loadImage(first_front_image, "color", selectedBackImage);
                // hideLoadingIndicator();
                enableFormElements();
            });
        }
    });
}

function preloadImage(url) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = () => resolve(img);
        img.onerror = reject;
        img.src = url;
    });
}

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

                    img.controls = {
                        ...fabric.Object.prototype.controls,
                        deleteControl:
                            fabric.Object.prototype.controls.deleteControl,
                    };

                    canvas.add(img);
                    canvas.setActiveObject(img);

                    save_side();
                    save_state(state.current_image_url);
                    emitAddedToCanvas();
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

        img.controls = {
            ...fabric.Object.prototype.controls,
            deleteControl: fabric.Object.prototype.controls.deleteControl,
        };

        canvas.add(img);
        canvas.setActiveObject(img);
        canvas.requestRenderAll();
        save_state(state.current_image_url);
        emitAddedToCanvas();
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
    front_assets: "",
    back_assets: "",
};

function handleAddToCart() {
    ["#addToCart", "#addToCartMobile"].forEach((selector) => {
        document
            .querySelector(selector)
            .addEventListener("click", async function (e) {
                e.preventDefault();

                const currentSide = state.current_image_side;

                try {
                    await saveDesignAndImage(currentSide);

                    if (selectedBackImage) {
                        if (currentSide === "front") {
                            loadImage(selectedBackImage, "pos");
                            setTimeout(async () => {
                                await saveDesignAndImage("back");
                                proceedWithAddToCart();
                            }, 500);
                        } else if (currentSide === "back") {
                            loadImage(selectedFrontImage, "pos");
                            setTimeout(async () => {
                                await saveDesignAndImage("front");
                                proceedWithAddToCart();
                            }, 500);
                        }
                    } else {
                        proceedWithAddToCart();
                    }
                } catch (err) {
                    alert("Failed to save design before adding to cart.");
                    console.error(err);
                }
            });
    });
}

function saveDesignAndImage(side) {
    return new Promise((resolve, reject) => {
        try {
            canvas.setZoom(1);
            canvas.setViewportTransform([1, 0, 0, 1, 0, 0]);
            canvas.requestRenderAll();

            const stateKey =
                side === "front" ? front_state_key : back_state_key;
            localStorage.setItem(stateKey, JSON.stringify(canvas.toJSON()));

            const tempCanvas = new fabric.Canvas(null, {
                width: canvas.width,
                height: canvas.height,
            });

            const objectsToAdd = canvas
                .getObjects()
                .filter(
                    (obj) =>
                        obj.type !== "rect" &&
                        obj.type !== "group" &&
                        !obj?.product_image
                );

            const objectData = objectsToAdd.map((obj) => obj.toObject());

            fabric.util.enlivenObjects(objectData, function (enlivenedObjects) {
                enlivenedObjects.forEach((obj) => tempCanvas.add(obj));
                tempCanvas.requestRenderAll();

                const assetsImageData = tempCanvas.toDataURL({
                    format: "png",
                    quality: 1,
                    backgroundColor: "transparent",
                });

                if (side === "front") {
                    final_design.front_assets = assetsImageData;
                } else {
                    final_design.back_assets = assetsImageData;
                }

                tempCanvas.dispose();

                // Save design image
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
                });

                canvas.requestRenderAll();

                if (side === "front") {
                    final_design.front_image = imageData;
                } else {
                    final_design.back_image = imageData;
                }

                resolve(); // 🎯 We're done here
            });
        } catch (err) {
            console.error("Error saving design:", err);
            reject(err);
        }
    });
}

function proceedWithAddToCart() {
    if (!final_design.front_image) {
        alert("Please save your design first");
        return;
    }

    const backImage = final_design.back_image || null;
    const front_assets = final_design.front_assets || null;
    const back_assets = final_design.back_assets || null;

    let size_input = document.querySelector("#sizeSelect");
    const size = size_input.value;

    let form = {
        front_image: final_design.front_image,
        back_image: backImage,
        front_assets: front_assets,
        back_assets: back_assets,
        product_id: product_image.getAttribute("data-id"),
        v_hash: localStorage.getItem("v_hash"),
        quantity: localStorage.getItem("quantity") || 1,
        price: null,
        default_img: 0,
        size: size,
    };

    let formData = new FormData();
    formData.append("front_image", form.front_image);
    formData.append("front_assets", form.front_assets);
    formData.append("product_id", form.product_id);
    formData.append("v_hash", form.v_hash);
    formData.append("quantity", form.quantity);
    formData.append("default_img", form.default_img);

    if (backImage) {
        formData.append("back_image", backImage);
    }

    if (back_assets) {
        formData.append("back_assets", back_assets);
    }

    // console.log("form: ", form);

    axios
        .post("/cart", formData)
        .then((response) => {
            alert("Item successfully added to cart");
            let count = document.getElementById("cart-count").textContent;
            // console.log("count is: ", count);
            count++;
            document.getElementById("cart-count").textContent = count;
        })
        .catch((error) => {
            console.error("Error adding to cart:", error);
            alert("There was an error adding the item to cart");
        });
}
window.addClipArtToCanvas = addClipArtToCanvas;
