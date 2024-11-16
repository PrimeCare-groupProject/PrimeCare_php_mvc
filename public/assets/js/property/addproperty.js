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
    const fileInput = document.getElementById('property_images');
    const dataTransfer = new DataTransfer();

    // Add all files except the removed one back to the input
    Array.from(fileInput.files).forEach((file, i) => {
        if (i !== index) dataTransfer.items.add(file);
    });

    fileInput.files = dataTransfer.files; // Update file input with modified files list
    handleFileSelect({ target: fileInput }); // Refresh preview
}


function handleFileSelectForDocs(event) {
    const file = event.target.files[0]; // Get only the first selected file
    const previewContainer = document.getElementById('preview-container-docs');
    
    previewContainer.innerHTML = ''; // Clear previous preview

    if (file && file.type === 'application/pdf') {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'img-preview-docs';
            imgContainer.innerHTML = `
                <img src="${ROOT}/assets/images/pdf.png" alt="Selected Image" class="preview-img">
                <p>${file.name}</p>
                <img src="${ROOT}/assets/images/close.png" class="remove-img-black" onclick="removeImageDocs()">
            `;
            previewContainer.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    } else {
        const imgContainer = document.createElement('div');
            imgContainer.className = 'img-preview-error';
            imgContainer.innerHTML = `<p class="image-preview-error-p">Please select a PDF file.</p>`;
            previewContainer.appendChild(imgContainer);
        //alert("Please select a PDF file.");
        event.target.value = ''; // Clear the file input if it's not a PDF
    }
}

function removeImageDocs() {
    const fileInput = document.getElementById('ownership_details');
    fileInput.value = ''; // Clear the file input
    document.getElementById('preview-container-docs').innerHTML = ''; // Clear the preview
}
