import "./bootstrap";
import "/node_modules/@fortawesome/fontawesome-free/css/all.min.css";

document.addEventListener("DOMContentLoaded", async function () {
    ensureVisitorTracking();
    let current_url = window.location.href;
    console.log("current_url: ", current_url);
    if (current_url.includes("customize")) {
         const { default: main } = await import("./main");
        main();
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
                console.log("Updated visitor hash from server:", serverHash);
            } else {
                console.log("Visitor hash verified:", v_hash);
            }
        } else {
            const createResponse = await axios.post("/api/visitor/create");
            v_hash = createResponse.data.v_hash;
            localStorage.setItem("v_hash", v_hash);
            console.log("Created new visitor hash:", v_hash);
        }

        return v_hash;
    } catch (error) {
        console.error("Visitor tracking error:", error);
        return localStorage.getItem("v_hash");
    }
}
