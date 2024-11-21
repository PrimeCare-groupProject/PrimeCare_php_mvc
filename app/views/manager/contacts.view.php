<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Contact Support</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input 
                type="text" 
                class="search-input" 
                name="searchterm" 
                placeholder="Search contact message ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>
<div>
    <?php require __DIR__ . '/../components/messageCard.php'; ?>
    <?php require __DIR__ . '/../components/messageCard.php'; ?>
    <?php require __DIR__ . '/../components/messageCard.php'; ?>
</div>

<?php require_once 'managerFooter.view.php'; ?>
<script>
    document.querySelectorAll('.service-card').forEach(card => {
    const replyBoxLabel = card.querySelector(".replyBoxLabel");
    const replyBoxSpan = card.querySelector(".replyBoxSpan");
    const completeBtn = card.querySelector("#complete-button");
    const cancelBtn = card.querySelector("#cancel-button");
    const mailBtn = card.querySelector("#sendMail-button");
    const submitBtn = card.querySelector("#submit-button");

    mailBtn.addEventListener("click", () => {
        replyBoxLabel.style.display = "block";
        replyBoxSpan.style.display = "block";
        completeBtn.style.display = "none";
        cancelBtn.style.display = "block";
        submitBtn.style.display = "block";
        mailBtn.style.display = "none";
    });

    cancelBtn.addEventListener("click", () => {
        replyBoxLabel.style.display = "none";
        replyBoxSpan.style.display = "none";
        completeBtn.style.display = "block";
        cancelBtn.style.display = "none";
        submitBtn.style.display = "none";
        mailBtn.style.display = "block";
    });

    submitBtn.addEventListener("click", () => {
        replyBoxLabel.style.display = "none";
        replyBoxSpan.style.display = "none";
        completeBtn.style.display = "block";
        cancelBtn.style.display = "none";
        submitBtn.style.display = "none";
        mailBtn.style.display = "block";
    });
});
</script>
