document.addEventListener("DOMContentLoaded", function () {
    const quantityInput = document.getElementById("quantity");
    const zoomLevel = document.getElementById("zoom-level");
    const productImage = document.getElementById("product-image");

    if (!quantityInput || !zoomLevel || !productImage) {
        return;
    }

    let zoom = 100;

    document.getElementById("increment").addEventListener("click", function () {
        quantityInput.value = parseInt(quantityInput.value) + 1;
    });

    document.getElementById("decrement").addEventListener("click", function () {
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });

    document.getElementById("zoom-in").addEventListener("click", function () {
        zoom += 10;
        zoomLevel.textContent = `${zoom}%`;
        productImage.style.transform = `scale(${zoom / 100})`;
    });

    document.getElementById("zoom-out").addEventListener("click", function () {
        if (zoom > 50) {
            zoom -= 10;
            zoomLevel.textContent = `${zoom}%`;
            productImage.style.transform = `scale(${zoom / 100})`;
        }
    });
});
