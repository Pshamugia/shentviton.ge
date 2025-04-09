document.addEventListener("DOMContentLoaded", function () {
    const zoomLevel = document.getElementById("zoom-level");
    const productImage = document.getElementById("product-image");

    if ( !zoomLevel || !productImage) {
        return;
    }

    let zoom = 100;

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
