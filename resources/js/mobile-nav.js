const isMobile = () => window.innerWidth <= 1024;

function qs(arg) {
    return document.querySelector(arg);
}

const tabButtons = {
    product: qs("#defaultOpen"),
    uploader: qs("#uploadBtn"),
    cliparts: qs("#clipartBtn"),
    text: qs("#textBtn"),
};

const tabContents = {
    product: qs("#product"),
    uploader: qs("#uploader"),
    cliparts: qs("#cliparts"),
    text: qs("#text"),
    canvas: qs("#canvasContainer"),
};

const btnContentMap = {
    product: tabContents.product,
    uploader: tabContents.uploader,
    cliparts: tabContents.cliparts,
    text: tabContents.text,
};

const buttonToTabName = new Map();

initMobileNav();

document.addEventListener("addedToCanvas", function (e) {
    Object.values(tabButtons).forEach((btn) => btn.classList.remove("active"));

    tabButtons?.canvas?.classList.add("active");

    Object.values(tabContents).forEach((content) =>
        content.classList.add("d-none")
    );

    const selectedContent = tabContents['canvas'];
    if (selectedContent) {
        selectedContent.classList.remove("d-none");
    }
});

window.addEventListener("resize", function () {
    if (isMobile()) {
        initMobileNav();
    } else {
        document.body.classList.remove("mobile-view");
        Object.values(tabContents).forEach((content) =>
            content.classList.remove("d-none")
        );
    }
});

function initMobileNav() {
    if (!isMobile()) return;
    document.body.classList.add("mobile-view");

    const canvasContainer = qs("#canvasContainer");

    if (!qs("#canvasBtn")) {
        const canvasBtn = document.createElement("button");
        canvasBtn.id = "canvasBtn";
        canvasBtn.className = "tablinks icon-color active";

        const icon = document.createElement("i");
        icon.className = "bi bi-easel icon-color";
        icon.style.fontSize = "20px";

        const lineBreak = document.createElement("br");
        const text = document.createTextNode("კანვასი");

        canvasBtn.appendChild(icon);
        canvasBtn.appendChild(lineBreak);
        canvasBtn.appendChild(text);

        const tabContainer = qs(".tab");
        tabContainer.insertBefore(canvasBtn, tabContainer.firstChild);

        tabButtons.canvas = canvasBtn;
        tabContents.canvas = canvasContainer;
        btnContentMap.canvas = canvasContainer;
    }

    Object.keys(tabButtons).forEach((key) => {
        buttonToTabName.set(tabButtons[key], key);
    });

    Object.values(tabContents).forEach((content) =>
        content.classList.add("d-none")
    );

    tabContents.canvas.classList.remove("d-none");

    Object.values(tabButtons).forEach((btn) => {
        btn.addEventListener("click", switchTabContent);
    });
}

function switchTabContent(evt) {
    evt.preventDefault();

    const clickedBtn = evt.currentTarget;
    const tabName = buttonToTabName.get(clickedBtn);

    if (!tabName) return;

    Object.values(tabButtons).forEach((btn) => btn.classList.remove("active"));

    clickedBtn.classList.add("active");

    Object.values(tabContents).forEach((content) =>
        content.classList.add("d-none")
    );

    const selectedContent = tabContents[tabName];
    if (selectedContent) {
        selectedContent.classList.remove("d-none");
    }
}
