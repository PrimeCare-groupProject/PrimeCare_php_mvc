<?php require 'customerHeader.view.php' ?>

<style>
.service-request-form-container {
    max-width: 540px;
    margin: 40px auto 60px auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(241,196,15,0.10);
    padding: 36px 36px 28px 36px;
    position: relative;
}
.selected-service-details {
    background: #f7f7e7;
    border-radius: 10px;
    padding: 18px 22px;
    margin-bottom: 28px;
    box-shadow: 0 1px 6px rgba(241,196,15,0.07);
    border-left: 5px solid #f1c40f;
}
.selected-service-details h3 {
    margin: 0 0 10px 0;
    font-size: 1.25rem;
    color: #2c3e50;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.selected-service-details p {
    margin: 4px 0;
    color: #444;
    font-size: 1.05rem;
}
.service-request-form label {
    display: block;
    margin-bottom: 14px;
    color: #222;
    font-weight: 500;
    letter-spacing: 0.2px;
}
.service-request-form input[type="text"],
.service-request-form input[type="date"],
.service-request-form input[type="number"],
.service-request-form textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    margin-top: 4px;
    margin-bottom: 10px;
    font-size: 1rem;
    background: #fafbfc;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.service-request-form input[type="text"]:focus,
.service-request-form input[type="date"]:focus,
.service-request-form input[type="number"]:focus,
.service-request-form textarea:focus {
    border-color: #f1c40f;
    box-shadow: 0 0 0 2px rgba(241,196,15,0.13);
    outline: none;
}
.service-request-form textarea {
    min-height: 80px;
    resize: vertical;
}
.service-request-form input[readonly] {
    background: #f8f8f8;
    color: #888;
    font-weight: 500;
}
.service-request-form .primary-btn {
    width: 100%;
    margin-top: 22px;
    padding: 12px 0;
    font-size: 1.13rem;
    background: linear-gradient(90deg, #f1c40f 60%, #f7d774 100%);
    color: #222;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(241,196,15,0.08);
    transition: background 0.2s, color 0.2s;
}
.service-request-form .primary-btn:hover {
    background: linear-gradient(90deg, #f39c12 60%, #f1c40f 100%);
    color: #fff;
}
.error-messages {
    background: #ffeaea;
    color: #b30000;
    border: 1px solid #ffb3b3;
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 18px;
    font-size: 1rem;
}
input[type="file"] {
    display: none;
}
.custom-file-upload {
    display: inline-block;
    padding: 8px 18px;
    cursor: pointer;
    background: #f7f7e7;
    color: #f1c40f;
    border-radius: 6px;
    font-weight: 600;
    font-size: 1rem;
    border: 1.5px dashed #f1c40f;
    margin-top: 4px;
    margin-bottom: 10px;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.custom-file-upload:hover {
    background: #fffbe6;
    color: #f39c12;
    border: 1.5px solid #f1c40f;
}
.upload-progress-bar-container {
    width: 100%;
    background: #f7f7e7;
    border-radius: 6px;
    margin: 10px 0 18px 0;
    height: 18px;
    display: none;
    overflow: hidden;
}
.upload-progress-bar {
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, #f1c40f 60%, #f39c12 100%);
    border-radius: 6px;
    transition: width 0.3s;
}
.upload-progress-label {
    position: absolute;
    left: 50%;
    top: 0;
    transform: translateX(-50%);
    font-size: 0.95rem;
    color: #444;
    font-weight: 500;
}
@media (max-width: 700px) {
    .service-request-form-container {
        padding: 18px 6vw 18px 6vw;
    }
}
</style>

<div class="user_view-menu-bar">
    <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Request Service for External Property</h2>
</div>

<div class="service-request-form-container">
    <div class="selected-service-details">
        <h3><i class="fa-solid fa-screwdriver-wrench" style="color:#f1c40f"></i> Selected Service</h3>
        <p><strong>Service Type:</strong> <?= esc($service_type ?? '') ?></p>
        <p><strong>Cost per Hour:</strong> <?= esc($cost_per_hour ?? '') ?> LKR</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="service-request-form" id="serviceRequestForm">
        <input type="hidden" name="service_id" value="<?= esc($service_id ?? '') ?>">
        <label>Service Type:
            <input type="text" name="service_type" value="<?= esc($service_type ?? '') ?>" readonly required>
        </label>
        <label>Cost per Hour:
            <input type="number" name="cost_per_hour" value="<?= esc($cost_per_hour ?? '') ?>" step="0.01" readonly required>
        </label>
        <label>Property Address:
            <input type="text" name="property_address" required>
        </label>
        <label>Description:
            <textarea name="property_description"></textarea>
        </label>
        <label>Property Images:
            <label for="property_images" class="custom-file-upload">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Images (max 3)
            </label>
            <input type="file" id="property_images" name="property_images[]" multiple required accept="image/*">
            <div class="upload-progress-bar-container" id="uploadProgressContainer">
                <div class="upload-progress-bar" id="uploadProgressBar"></div>
                <span class="upload-progress-label" id="uploadProgressLabel"></span>
            </div>
            <span id="selected-files" style="display:block; margin-top:6px; color:#888; font-size:0.97rem;"></span>
            <span id="file-error" style="color:#b30000; font-size:0.98rem; display:none;"></span>
            <div id="image-preview-container" style="display:flex; gap:12px; flex-wrap:wrap; margin-top:10px;"></div>
        </label>
        <button type="submit" class="primary-btn" id="submitBtn"><i class="fa-solid fa-paper-plane"></i> Submit Request</button>
    </form>
</div>

<script>
const fileInput = document.getElementById('property_images');
const fileLabel = document.querySelector('.custom-file-upload');
const selectedFiles = document.getElementById('selected-files');
const fileError = document.getElementById('file-error');
const form = document.getElementById('serviceRequestForm');
const progressContainer = document.getElementById('uploadProgressContainer');
const progressBar = document.getElementById('uploadProgressBar');
const progressLabel = document.getElementById('uploadProgressLabel');
const submitBtn = document.getElementById('submitBtn');
const previewContainer = document.getElementById('image-preview-container');

let animationDone = false;
let filesArray = [];

fileLabel.addEventListener('click', function(e) {
    e.preventDefault();
    fileInput.click();
});

function updatePreview() {
    previewContainer.innerHTML = '';
    filesArray.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.style.position = 'relative';
            div.style.display = 'inline-block';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '80px';
            img.style.height = '80px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '8px';
            img.style.border = '1.5px solid #f1c40f';

            const removeBtn = document.createElement('span');
            removeBtn.textContent = '×';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '-8px';
            removeBtn.style.right = '-8px';
            removeBtn.style.background = '#fff';
            removeBtn.style.color = '#b30000';
            removeBtn.style.border = '1px solid #b30000';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.width = '22px';
            removeBtn.style.height = '22px';
            removeBtn.style.display = 'flex';
            removeBtn.style.alignItems = 'center';
            removeBtn.style.justifyContent = 'center';
            removeBtn.style.cursor = 'pointer';
            removeBtn.style.fontWeight = 'bold';
            removeBtn.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
            removeBtn.title = 'Remove';

            removeBtn.onclick = function() {
                filesArray.splice(idx, 1);
                updatePreview();
                updateFileInput();
            };

            div.appendChild(img);
            div.appendChild(removeBtn);
            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
    selectedFiles.textContent = filesArray.length ? filesArray.map(f => f.name).join(', ') : '';
    fileError.style.display = filesArray.length === 0 ? "block" : "none";
    fileError.textContent = filesArray.length === 0 ? "You must upload at least 1 image." : "";
}

function updateFileInput() {
    // Create a new DataTransfer to update the file input
    const dt = new DataTransfer();
    filesArray.forEach(file => dt.items.add(file));
    fileInput.files = dt.files;
}

fileInput.addEventListener('change', function() {
    // Add new files, but limit to 3
    let newFiles = Array.from(fileInput.files);
    filesArray = filesArray.concat(newFiles).slice(0, 3);

    if (filesArray.length > 3) {
        fileError.textContent = "You can upload a maximum of 3 images.";
        fileError.style.display = "block";
        filesArray = filesArray.slice(0, 3);
    } else {
        fileError.style.display = "none";
    }
    updatePreview();
    updateFileInput();

    // Show animation on selection
    if (filesArray.length > 0) {
        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';
        progressLabel.textContent = 'Uploading... 0%';
        let progress = 0;
        function animateProgress() {
            progress += Math.random() * 18 + 8;
            if (progress > 100) progress = 100;
            progressBar.style.width = progress + '%';
            progressLabel.textContent = 'Uploading... ' + Math.floor(progress) + '%';
            if (progress < 100) {
                setTimeout(animateProgress, 120);
            } else {
                progressLabel.textContent = 'Upload complete!';
                setTimeout(() => {
                    progressContainer.style.display = 'none';
                }, 500);
            }
        }
        animateProgress();
    }
});

form.addEventListener('submit', function(e) {
    if (animationDone) return;
    if (filesArray.length === 0) {
        fileError.textContent = "You must upload at least 1 image.";
        fileError.style.display = "block";
        e.preventDefault();
        return false;
    }
    if (filesArray.length > 3) {
        fileError.textContent = "You can upload a maximum of 3 images.";
        fileError.style.display = "block";
        e.preventDefault();
        return false;
    }
    // Allow normal submit
});

// Success popup (after submit, in PHP)
<?php if(isset($_SESSION['flash']['msg']) && $_SESSION['flash']['type'] == 'success'): ?>
window.addEventListener('DOMContentLoaded', function() {
    const modal = document.createElement('div');
    modal.className = 'success-modal-overlay';
    modal.innerHTML = `
        <div class="success-modal-content">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h2>Success!</h2>
            <p><?= $_SESSION['flash']['msg'] ?></p>
            <p id="countdown" class="countdown">Redirecting in 5 seconds...</p>
            <button class="modal-close-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/requestService'">Continue</button>
        </div>
    `;
    document.body.appendChild(modal);
    let timeLeft = 5;
    const countdownElement = document.getElementById('countdown');
    const countdown = setInterval(() => {
        timeLeft--;
        countdownElement.textContent = `Redirecting in ${timeLeft} seconds...`;
        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = '<?= ROOT ?>/dashboard/requestService';
        }
    }, 1000);
});
<?php unset($_SESSION['flash']); endif; ?>
</script>

<style>
/* Success modal styles */
.success-modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    display: flex; justify-content: center; align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease-out;
}
.success-modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 400px;
    text-align: center;
    animation: slideUp 0.4s ease-out;
}
.success-icon {
    margin-bottom: 20px;
}
.success-modal-content h2 {
    color: #4CAF50;
    margin-bottom: 15px;
    font-size: 24px;
}
.success-modal-content p {
    margin-bottom: 20px;
    color: #555;
    font-size: 16px;
}
.countdown {
    font-size: 14px;
    color: #777;
    margin-top: 5px;
}
.modal-close-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.2s;
}
.modal-close-btn:hover {
    background-color: #3e8e41;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<?php require 'customerFooter.view.php' ?>