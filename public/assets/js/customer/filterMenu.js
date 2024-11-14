const menuBtn = document.getElementById("filter-menu-btn");
const filterItems = document.querySelectorAll("#hided-menu");

function toggleMenu() {
    if (menuBtn.classList.contains("rotate-up")) {
        menuBtn.classList.remove("rotate-up");
        filterItems.forEach(item => item.classList.add("hide-items"));
    } else {
        menuBtn.classList.add("rotate-up");
        filterItems.forEach(item => item.classList.remove("hide-items"));
    }
}
