<?php
// Get candidate ID from URL parameter
$candidateId = $_GET['id'] ?? null;

if (!$candidateId) {
    echo '<div class="alert alert-danger">No candidate ID provided.</div>';
    return;
}

// Fetch existing postgraduate trainings for this candidate
// You'll need to implement this function based on your database structure
// $postgrads = fetchPostgradsByCandidateId($candidateId);
$postgrads = []; // Placeholder - replace with actual data
?>

<h5 class="mt-4">Postgraduate Training Attended</h5>

<!-- Existing Postgraduate Trainings List -->
<div id="postgrad-list" class="mb-3">
    <?php if (!empty($postgrads)): ?>
        <?php foreach ($postgrads as $postgrad): ?>
            <div class="card mb-2" id="postgrad-<?= $postgrad['id'] ?>">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($postgrad['post_training_school']) ?></strong><br>
                            <span class="text-primary"><?= htmlspecialchars($postgrad['degree']) ?></span><br>
                            <small class="text-muted">
                                <?= date('M Y', strtotime($postgrad['start_date'])) ?> - 
                                <?= date('M Y', strtotime($postgrad['end_date'])) ?>
                            </small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editPostgrad(<?= $postgrad['id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deletePostgrad(<?= $postgrad['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-muted" id="no-postgrads">No postgraduate trainings added yet.</div>
    <?php endif; ?>
</div>

<!-- Add New Button -->
<button class="btn btn-success btn-sm mb-2" id="add-postgrad-btn">+ Add Postgraduate Training</button>

<!-- Form Container -->
<div id="postgrad-form-container" style="display: none;">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Add Postgraduate Training</h6>
            <form id="postgradForm">
                <input type="hidden" name="candidate_id" value="<?= $candidateId ?>">
                <input type="hidden" name="postgrad_id" id="postgrad_id" value="">
                
                <div class="mb-2">
                    <label for="post_training_school" class="form-label">Post Training School</label>
                    <input type="text" name="post_training_school" id="post_training_school" class="form-control" placeholder="Post Training School" required>
                </div>
                
                <div class="mb-2">
                    <label for="postgrad_degree" class="form-label">Degree</label>
                    <input type="text" name="degree" id="postgrad_degree" class="form-control" placeholder="Degree" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="postgrad_start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="postgrad_start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="postgrad_end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="postgrad_end_date" class="form-control" required>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm" id="submit-postgrad">Save</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="cancel-postgrad">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Response Messages -->
<div id="postgrad-response" class="mt-2"></div>

<script>
(function() {
    const addBtn = document.getElementById('add-postgrad-btn');
    const formContainer = document.getElementById('postgrad-form-container');
    const form = document.getElementById('postgradForm');
    const cancelBtn = document.getElementById('cancel-postgrad');
    const responseDiv = document.getElementById('postgrad-response');

    // Show form
    addBtn.addEventListener('click', function() {
        formContainer.style.display = 'block';
        addBtn.style.display = 'none';
        clearForm();
    });

    // Hide form
    cancelBtn.addEventListener('click', function() {
        formContainer.style.display = 'none';
        addBtn.style.display = 'block';
        clearForm();
    });

    // Clear form
    function clearForm() {
        form.reset();
        document.getElementById('postgrad_id').value = '';
        document.querySelector('#postgradForm h6').textContent = 'Add Postgraduate Training';
        responseDiv.innerHTML = '';
    }

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const isEdit = document.getElementById('postgrad_id').value !== '';
        const url = isEdit ? 
            '/wacs_system/public/?url=candidates/update-postgraduate-training' : 
            '/wacs_system/public/?url=candidates/save-postgraduate-training';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                responseDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
                
                // Reload the postgrad list
                setTimeout(() => {
                    location.reload(); // Or implement dynamic list update
                }, 1000);
            } else {
                responseDiv.innerHTML = '<div class="alert alert-danger">' + result.message + '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            responseDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        });
    });

    // Edit postgrad
    window.editPostgrad = function(id) {
        fetch(`/wacs_system/public/?url=candidates/get-postgraduate-training&id=${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const postgrad = data.postgrad;
                document.getElementById('postgrad_id').value = postgrad.id;
                document.getElementById('post_training_school').value = postgrad.post_training_school;
                document.getElementById('postgrad_degree').value = postgrad.degree;
                document.getElementById('postgrad_start_date').value = postgrad.start_date;
                document.getElementById('postgrad_end_date').value = postgrad.end_date;
                
                document.querySelector('#postgradForm h6').textContent = 'Edit Postgraduate Training';
                formContainer.style.display = 'block';
                addBtn.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            responseDiv.innerHTML = '<div class="alert alert-danger">Failed to load postgraduate training data.</div>';
        });
    };

    // Delete postgrad
    window.deletePostgrad = function(id) {
        if (confirm('Are you sure you want to delete this postgraduate training?')) {
            fetch(`/wacs_system/public/?url=candidates/delete-postgraduate-training&id=${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById(`postgrad-${id}`).remove();
                    responseDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
                    
                    // Check if list is empty
                    const list = document.getElementById('postgrad-list');
                    if (list.children.length === 0) {
                        list.innerHTML = '<div class="text-muted" id="no-postgrads">No postgraduate trainings added yet.</div>';
                    }
                } else {
                    responseDiv.innerHTML = '<div class="alert alert-danger">' + result.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseDiv.innerHTML = '<div class="alert alert-danger">Failed to delete postgraduate training.</div>';
            });
        }
    };
})();
</script>

