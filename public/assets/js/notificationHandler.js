function showNotifications(event) {
    if (event) event.preventDefault(); // Prevent accidental form submission
}

document.addEventListener("DOMContentLoaded", function () {
    const notifButton = document.getElementById("notification_button");
    const notifArea = document.getElementById("notification_show_area");

    notifButton.addEventListener("click", function (e) {
        e.stopPropagation(); // prevent bubbling to document
        notifArea.classList.toggle("active_notification");

        // Mark notifications as read only when opening
        if (notifArea.classList.contains("active_notification")) {
            fetch(BASE_URL + "/Notification/readAll", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    notifButton.classList.remove("has-unread"); // Remove the red dot
                    // Optionally update individual notifications to "read" class
                    document.querySelectorAll('.NSA__item.unread').forEach(item => {
                        item.classList.remove('unread');
                        item.classList.add('read');
                    });
                }
            })
            .catch(error => {
                console.error("Error marking notifications as read:", error);
            });
        }
    });

    // Hide the notification area when clicking outside
    document.addEventListener("click", function (e) {
        if (!notifArea.contains(e.target) && !notifButton.contains(e.target)) {
            notifArea.classList.remove("active_notification");
        }
    });
});
