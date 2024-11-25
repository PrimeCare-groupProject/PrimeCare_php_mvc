document.addEventListener("DOMContentLoaded", () => {
    const adminContacts = document.getElementById("BP__admin-contacts");
    const toggleLeft = document.getElementById("BP__toggle_left");
    const toggleRight = document.getElementById("BP__toggle_right");

    toggleLeft.addEventListener("click", () => {
        adminContacts.classList.add("active");
    });

    toggleRight.addEventListener("click", () => {
        adminContacts.classList.remove("active");
    });
});
