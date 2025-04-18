import "./bootstrap";
import "/node_modules/@fortawesome/fontawesome-free/css/all.min.css";

document.addEventListener("DOMContentLoaded", async function () {
    ensureVisitorTracking();
    let current_url = window.location.href;
    if (current_url.includes("customize")) {
        await import("./mobile-nav");
        const { default: main } = await import("./main");
        main();
        cleanUp();
    }

    if (current_url.includes("home")) {
        //
    }
});

async function ensureVisitorTracking() {
    try {
        let v_hash = localStorage.getItem("v_hash");

        const response = await axios.get("/api/visitor/check");

        if (response.data.status === "success") {
            const serverHash = response.data.v_hash;

            if (!v_hash || v_hash !== serverHash) {
                localStorage.setItem("v_hash", serverHash);
            } else {
            }
        } else {
            const createResponse = await axios.post("/api/visitor/create");
            v_hash = createResponse.data.v_hash;
            localStorage.setItem("v_hash", v_hash);
        }

        return v_hash;
    } catch (error) {
        console.error("Visitor tracking error:", error);
        return localStorage.getItem("v_hash");
    }
}

function cleanUp() {
    Object.keys(localStorage).forEach((key) => {
        if (key.includes("/colors/") || key.includes("design")) {
            localStorage.removeItem(key);
        }
    });
}
