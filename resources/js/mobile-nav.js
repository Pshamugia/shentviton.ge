function qs(arg) {
    return document.querySelector(arg);
}

const isMobile = () => window.innerWidth <= 1024;

const tabButtons = {
    product: qs("#defaultOpen"),
    uploader: qs("#uploadBtn"),
    cliparts: qs("#clipartBtn"),
    text: qs("#textBtn"),
     canvas: null,
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

initNavigation();

document.addEventListener("addedToCanvas", function (e) {
    if (!isMobile()) return;

    Object.values(tabButtons).forEach((btn) => btn.classList.remove("active"));
    tabButtons?.canvas?.classList.add("active");

    Object.values(tabContents).forEach((content) =>
        content.classList.add("d-none")
    );

    const selectedContent = tabContents["canvas"];
    if (selectedContent) {
        selectedContent.classList.remove("d-none");
        if (isMobile()) {
            selectedContent.classList.add("tabcontent");
        }
    }
});

window.addEventListener("resize", function () {
    const wasMobile = document.body.classList.contains("mobile-view");
    const isMobileNow = isMobile();

    if (wasMobile !== isMobileNow) {
        resetNavigation();
        initNavigation();
    }
});

function initNavigation() {
    if (isMobile()) {
        initMobileNav();
    } else {
        initDesktopNav();
    }
}

function resetNavigation() {
    document.body.classList.remove("mobile-view");
    const canvasBtn = qs("#canvasBtn");
    if (canvasBtn) {
        canvasBtn.remove();
    }

    Object.values(tabButtons).forEach((btn) => {
        if (btn) {
            btn.removeEventListener("click", switchTabContent);
        }
    });

    Object.values(tabContents).forEach((content) => {
        if (content) {
            content.classList.remove("d-none", "tabcontent");
        }
    });

    buttonToTabName.clear();
}

function initMobileNav() {
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
        if (tabButtons[key]) {
            buttonToTabName.set(tabButtons[key], key);
        }
    });

    Object.values(tabContents).forEach((content) => {
        if (content) {
            content.classList.add("d-none");
        }
    });

    if (tabContents.canvas) {
        tabContents.canvas.classList.remove("d-none");
        tabContents.canvas.classList.add("tabcontent");
    }

    Object.values(tabButtons).forEach((btn) => {
        if (btn) {
            btn.addEventListener("click", switchTabContent);
        }
    });
}

function initDesktopNav() {
    if (tabContents.product) {
        tabContents.product.style.display = "block";
    }

    if (tabContents.canvas) {
        tabContents.canvas.classList.remove("tabcontent", "d-none");
        tabContents.canvas.style.display = "flex";
    }

    ["uploader", "cliparts", "text"].forEach((key) => {
        if (tabContents[key]) {
            tabContents[key].style.display = "none";
        }
    });

    // Set the default button as active
    if (tabButtons.product) {
        tabButtons.product.classList.add("active");
        ["uploader", "cliparts", "text"].forEach((key) => {
            if (tabButtons[key]) {
                tabButtons[key].classList.remove("active");
            }
        });
    }
}

function switchTabContent(evt) {
    evt.preventDefault();

    const clickedBtn = evt.currentTarget;
    const tabName = buttonToTabName.get(clickedBtn);

    if (!tabName || !tabContents[tabName]) return;

    Object.values(tabButtons).forEach((btn) => {
        if (btn) {
            btn.classList.remove("active");
        }
    });

    clickedBtn.classList.add("active");

    Object.values(tabContents).forEach((content) => {
        if (content) {
            content.classList.add("d-none");
        }
    });

    const selectedContent = tabContents[tabName];
    if (selectedContent) {
        selectedContent.classList.remove("d-none");

        // Only add tabcontent class in mobile view
        if (isMobile()) {
            selectedContent.classList.add("tabcontent");
        }
    }
}
