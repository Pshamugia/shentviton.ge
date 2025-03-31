import "./bootstrap";
import "/node_modules/@fortawesome/fontawesome-free/css/all.min.css";
import main from "./main";

document.addEventListener("DOMContentLoaded", function () {
    console.log("domcontendloaded");
    checkVisitor();
    let current_url = window.location.href;
    console.log("current_url: ", current_url);
    if (current_url.includes("customize")) {
        // main();
    }

    if (current_url.includes("home")) {
        //
    }

    
});

function checkVisitor() {
    let v_hash = localStorage.getItem("v_hash");

    console.log(v_hash);
    if (!v_hash) {
        axios
            .post("/api/visitor")
            .then((response) => {
                localStorage.setItem("v_hash", response.data.v_hash);
            })
            .catch((error) => {
                console.error(error);
            });
    }
}
