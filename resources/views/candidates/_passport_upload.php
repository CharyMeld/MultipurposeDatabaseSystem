<?php
// Get candidate ID from URL parameter
$candidateId = $_GET['id'] ?? null;

if (!$candidateId) {
    echo '<div class="alert alert-danger">No candidate ID provided.</div>';
    return;
}

// You might want to fetch candidate data here if needed
// $candidate = fetchCandidateById($candidateId);
?>
<div class="text-center mb-3">
    <form action="/wacs_system/public/?url=candidates/upload-passport&id=<?= $candidateId ?>" method="POST" enctype="multipart/form-data" id="passportUploadForm">
        <div id="passport-dropzone"
             class="border rounded d-flex align-items-center justify-content-center mx-auto passport-dropzone"
             style="width: 150px; height: 150px; cursor: pointer; position: relative;"
             title="Click to select passport image">
            <img id="passportPreview"
                 src="<?= !empty($candidate['passport']) ? htmlspecialchars($candidate['passport']) : 'https://via.placeholder.com/100?text=Click+to+Upload' ?>"
                 alt="Passport"
                 style="max-width: 100px; max-height: 100px; object-fit: contain; pointer-events: none;">
            <input type="file" name="passport" id="passportInput" accept="image/*" style="display: none;">
        </div>
        <small class="text-muted d-block mt-1">Click image area to select file or drag & drop</small>
        <button type="submit" class="btn btn-sm btn-primary mt-2" id="uploadButton">Upload</button>
    </form>
    <div id="uploadResponse" class="mt-2"></div>
</div>

<style>
.passport-dropzone {
    transition: all 0.2s ease;
}
.passport-dropzone:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}
.dropzone-hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}
</style>

<script>
(function() {
    function previewPassport(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('passportPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleDrop(event) {
        event.preventDefault();
        const dropzone = event.currentTarget;
        dropzone.classList.remove('border-primary', 'dropzone-hover');
        
        const files = event.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            const input = document.getElementById('passportInput');
            
            try {
                // Create a new FileList-like object and assign it to the input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                input.files = dataTransfer.files;
                
                // Preview the image
                previewPassport(input);
                
                // Show success message for file selection
                document.getElementById('uploadResponse').innerHTML = 
                    '<div class="alert alert-success">Image selected: ' + files[0].name + '</div>';
            } catch (error) {
                console.error('Error handling dropped file:', error);
                // Fallback: just show the preview without setting input.files
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('passportPreview').src = e.target.result;
                    document.getElementById('uploadResponse').innerHTML = 
                        '<div class="alert alert-info">Image preview loaded. Please use the upload button or try selecting the file manually.</div>';
                };
                reader.readAsDataURL(files[0]);
            }
        } else {
            // Show error message for invalid file type
            document.getElementById('uploadResponse').innerHTML = 
                '<div class="alert alert-warning">Please drop a valid image file.</div>';
        }
    }

    function setUploadButtonState(disabled, text) {
        const button = document.getElementById('uploadButton');
        if (button) {
            button.disabled = disabled;
            button.textContent = text;
        }
    }

    // Initialize passport upload functionality
    const dropzone = document.getElementById('passport-dropzone');
    const input = document.getElementById('passportInput');

    if (!dropzone || !input) {
        console.error('Passport upload elements not found');
        return;
    }

    // Function to trigger file input
    function openFileDialog(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Clear any existing response
        document.getElementById('uploadResponse').innerHTML = '';
        
        // Trigger the file input
        input.click();
    }

    // Add event listeners
    dropzone.addEventListener('click', openFileDialog);

    // Drag over
    dropzone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropzone.classList.add('border-primary', 'dropzone-hover');
    });

    // Drag leave
    dropzone.addEventListener('dragleave', function () {
        dropzone.classList.remove('border-primary', 'dropzone-hover');
    });

    // Drop
    dropzone.addEventListener('drop', handleDrop);

    // Preview when selected manually
    input.addEventListener('change', function () {
        previewPassport(this);
        // Clear any previous error messages when a file is selected
        if (this.files && this.files[0]) {
            document.getElementById('uploadResponse').innerHTML = 
                '<div class="alert alert-success">Image selected: ' + this.files[0].name + '</div>';
        }
    });

    // AJAX form submit with validation
    document.getElementById('passportUploadForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const input = document.getElementById('passportInput');
        const responseDiv = document.getElementById('uploadResponse');
        
        // Check if a file is selected
        if (!input.files || input.files.length === 0) {
            responseDiv.innerHTML = '<div class="alert alert-warning">Please select a passport image to upload.</div>';
            return;
        }
        
        // Check if the selected file is an image
        const file = input.files[0];
        if (!file.type.startsWith('image/')) {
            responseDiv.innerHTML = '<div class="alert alert-warning">Please select a valid image file.</div>';
            return;
        }
        
        // Show loading message and disable button
        responseDiv.innerHTML = '<div class="alert alert-info">Uploading passport...</div>';
        setUploadButtonState(true, 'Uploading...');
        
        const formData = new FormData(form);
        const actionUrl = form.action;

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // This tells the server it's an AJAX request
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json(); // Expect JSON response from the updated controller
        })
        .then(result => {
            setUploadButtonState(false, 'Upload');
            
            if (result.success) {
                responseDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
                
                // Update the preview image with the new passport if URL is provided
                if (result.passport_url) {
                    document.getElementById('passportPreview').src = result.passport_url + '?t=' + Date.now();
                }
                
                // Clear the file input
                input.value = '';
            } else {
                responseDiv.innerHTML = '<div class="alert alert-danger">' + result.message + '</div>';
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            setUploadButtonState(false, 'Upload');
            responseDiv.innerHTML = '<div class="alert alert-danger">Upload failed. Please check your connection and try again. Error: ' + error.message + '</div>';
        });
    });
})();
</script>
