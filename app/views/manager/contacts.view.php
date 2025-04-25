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
    <?#php show($messages) ?>
    <?php if (empty($messages)) : ?>
        <div class="input-group" style="text-align: center; width: 100%; display: flow;">
            <p>No messages</p>
        </div>
    <?php endif; ?>
    <?php foreach ($messages as $groupedMessage) : ?>
    <?#php show($groupedMessage); ?>
    <div class="service-card">
        <div class="service-card-header">
            <!-- <h3><#?= esc($groupedMessage->personDetails->name) ?></h3> -->
        </div>
        <div class="service-card-content">
            <div class="field-group">
                <div class="field-group">
                    <label class="service-card-label">ID:</label>
                    <span class="service-card-field"><?= esc($groupedMessage->personDetails->pid) ?></span>
                </div>
                <div class="field-group">
                    <label class="service-card-label">Date:</label>
                    <span class="service-card-field">25/11/2024</span>
                </div>
            </div>
            <div class="field-group">
                <div class="field-group">
                    <label class="service-card-label">Phone:</label>
                    <span class="service-card-field"><?= esc($groupedMessage->personDetails->contactNo) ?></span>
                </div>
                <div class="field-group">
                    <label class="service-card-label">Name:</label>
                    <span class="service-card-field"><?= esc($groupedMessage->personDetails->name) ?></span>
                </div>
            </div> 

            <div class="field-group">
                <label class="service-card-label">Message:</label>
                <textarea class="service-card-field" rows="<?= $groupedMessage->count ?>" readonly><?= esc($groupedMessage->messages) ?></textarea>
            </div>

            <form method="POST">
                <div class="field-group" style="margin-top: 10px;">
                    <label class="service-card-label replyBoxLabel" style="display:none;">Email Message:</label>
                    <input type="text" class="service-card-field replyBoxSpan" name="emailMessage" style="display:none;">
                </div>

                <input type="hidden" name="replyMessage" value="1">
                <input type="hidden" name="name" value="<?=$groupedMessage->personDetails->name?>">
                <input type="hidden" name="pid" value="<?=$groupedMessage->personDetails->pid?>">
                <input type="hidden" name="email" value="<?= esc($groupedMessage->personDetails->email) ?>">
                <input type="hidden" name="complete" value="0" class="complete-input">
                
                <div class="input-group-aligned">
                    <button type="button" class="secondary-btn green" id="complete-button">Complete</button>
                    <button type="button" class="secondary-btn red" id="cancel-button" style="display: none;">Cancel</button>
                        
                    <button type="button" class="secondary-btn" id="sendMail-button" hidden>Send email</button>
                    <button type="submit" class="primary-btn" id="submit-button" style="display: none;">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <?#php require __DIR__ . '/../components/messageCard.php'; ?>
    <?#php require __DIR__ . '/../components/messageCard.php'; ?>
    <?#php require __DIR__ . '/../components/messageCard.php'; ?>
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
        const completeInput = card.querySelector(".complete-input");

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

        completeBtn.addEventListener("click", () => {
            completeInput.value = "1";
            card.querySelector("form").submit();
        });
    });
</script>
<script src="<?= ROOT ?>/assets/js/loader.js"></script>

