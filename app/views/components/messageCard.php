<div class="service-card">
    <div class="service-card-header">
        <h3>Message No 0</h3>
    </div>
    <div class="service-card-content">
        <div class="field-group">
            <div class="field-group">
                <label class="service-card-label">ID:</label>
                <span class="service-card-field">11320</span>
            </div>
            <div class="field-group">
                <label class="service-card-label">Date:</label>
                <span class="service-card-field">25/11/2024</span>
            </div>
        </div>
        <div class="field-group">
            <div class="field-group">
                <label class="service-card-label">Phone:</label>
                <span class="service-card-field">077777777</span>
            </div>
            <div class="field-group">
                <label class="service-card-label">Name:</label>
                <span class="service-card-field">Customer name</span>
            </div>
        </div> 

        <div class="field-group">
            <label class="service-card-label">Message:</label>
            <span class="service-card-field">Message...</span>
        </div>

        <div class="field-group" style="margin-top: 10px;">
            <label class="service-card-label replyBoxLabel" style="display:none;">Email Message:</label>
            <span class="service-card-field replyBoxSpan" style="display:none;">Email Message...</span>
        </div>
        <div class="input-group-aligned">
            <button type="button" class="secondary-btn green" id="complete-button">Complete</button>
            <button type="button" class="secondary-btn red" id="cancel-button" style="display: none;">Cancel</button>
                
            <button type="button" class="secondary-btn" id="sendMail-button">Send email</button>
            <button type="submit" class="primary-btn" id="submit-button" style="display: none;">Submit</button>
        </div>
    </div>
</div>

<!-- <script>
    const replyBoxLabel = document.querySelector(".replyBoxLabel");
    const replyBoxSpan = document.querySelector(".replyBoxSpan");
    const completeBtn = document.querySelector("#complete-button");
    const cancelBtn = document.querySelector("#cancel-button");
    const mailBtn = document.querySelector("#sendMail-button");
    const submitBtn = document.querySelector("#submit-button");

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

</script> -->
