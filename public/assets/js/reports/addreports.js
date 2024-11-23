function handleFileSelect(event) {
    const maxFiles = 6;
    const files = event.target.files;
    const previewContainer = document.getElementById('preview-container');
    
    previewContainer.innerHTML = ''; // Clear previous previews

    if (files.length > maxFiles) {
        //alert(`You can only select up to ${maxFiles} images.`);
        const imgContainer = document.createElement('div');
            imgContainer.className = 'img-preview-error';
            imgContainer.innerHTML = `<p class="image-preview-error-p">You can only select up to ${maxFiles} images.</p>`;
            previewContainer.appendChild(imgContainer);
        event.target.value = ''; // Clear file input if limit exceeded
        return;
    }

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'img-preview';
            imgContainer.innerHTML = `
                <img src="${e.target.result}" alt="Selected Image" class="preview-img">
                <img src="${ROOT}/assets/images/close.png" class="remove-img" onclick="removeImage(${index})">
            `;
            previewContainer.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    const fileInput = document.getElementById('reports_images');
    const dataTransfer = new DataTransfer();

    // Add all files except the removed one back to the input
    Array.from(fileInput.files).forEach((file, i) => {
        if (i !== index) dataTransfer.items.add(file);
    });

    fileInput.files = dataTransfer.files; // Update file input with modified files list
    handleFileSelect({ target: fileInput }); // Refresh preview
}
