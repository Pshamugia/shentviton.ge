export default function toggleSideBar(evt, el, action = null) {
    evt.preventDefault();

    if (action === null) {
        action = el.classList.contains("open") ? 0 : 1;
    }

    el.classList.toggle("open", action > 0);
    el.classList.toggle("close", action <= 0);
}
