<?php
// Get candidate ID from URL parameter
$candidateId = $_GET['id'] ?? null;

if (!$candidateId) {
    echo '<div class="alert alert-danger">No candidate ID provided.</div>';
    return;
}

// Fetch existing medical schools for this candidate
// You'll need to implement this function based on your database structure
// $medicalSchools = fetchMedicalSchoolsByCandidateId($candidateId);
$medicalSchools = []; // Placeholder - replace with actual data
?>

<h5>Medical School Attended</h5>

<!-- Existing Medical Schools List -->
<div id="medical-school-list" class="mb-3">
    <?php if (!empty($medicalSchools)): ?>
        <?php foreach ($medicalSchools as $school): ?>
            <div class="card mb-2" id="medical-school-<?= $school['id'] ?>">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($school['medical_school']) ?></strong><br>
                            <small class="text-muted">
                                <?= date('M Y', strtotime($school['start_date'])) ?> - 
                                <?= date('M Y', strtotime($school['end_date'])) ?>
                            </small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editMedicalSchool(<?= $school['id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMedicalSchool(<?= $school['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-muted" id="no-medical-schools">No medical schools added yet.</div>
    <?php endif; ?>
</div>

<!-- Add New Button -->
<button class="btn btn-success btn-sm mb-2" id="add-medical-school-btn">+ Add Medical School</button>

<!-- Form Container -->
<div id="medical-school-form-container" style="display: none;">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Add Medical School</h6>
            <form id="medicalSchoolForm">
                <input type="hidden" name="candidate_id" value="<?= $candidateId ?>">
                <input type="hidden" name="medical_school_id" id="medical_school_id" value="">
                
                <div class="mb-2">
                    <label for="medical_school" class="form-label">Medical School</label>
                    <input type="text" name="medical_school" id="medical_school" class="form-control" placeholder="Medical School" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm" id="submit-medical-school">Save</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="cancel-medical-school">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Response Messages -->
<div id="medical-school-response" class="mt-2"></div>

<script>
(function() {
    const addBtn = document.getElementById('add-medical-school-btn');
    const formContainer = document.getElementById('medical-school-form-container');
    const form = document.getElementById('medicalSchoolForm');
    const cancelBtn = document.getElementById('cancel-medical-school');
    const responseDiv = document.getElementById('medical-school-response');

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
        document.getElementById('medical_school_id').value = '';
        document.querySelector('#medicalSchoolForm h6').textContent = 'Add Medical School';
        responseDiv.innerHTML = '';
    }

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const isEdit = document.getElementById('medical_school_id').value !== '';
        const url = isEdit ? 
            '/wacs_system/public/?url=candidates/update-medical-school' : 
            '/wacs_system/public/?url=candidates/save-medical-school';

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
                
                // Reload the medical school list
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

    // Edit medical school
    window.editMedicalSchool = function(id) {
        // You'll need to implement this to fetch the medical school data
        fetch(`/wacs_system/public/?url=candidates/get-medical-school&id=${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const school = data.medical_school;
                document.getElementById('medical_school_id').value = school.id;
                document.getElementById('medical_school').value = school.medical_school;
                document.getElementById('start_date').value = school.start_date;
                document.getElementById('end_date').value = school.end_date;
                
                document.querySelector('#medicalSchoolForm h6').textContent = 'Edit Medical School';
                formContainer.style.display = 'block';
                addBtn.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            responseDiv.innerHTML = '<div class="alert alert-danger">Failed to load medical school data.</div>';
        });
    };

    // Delete medical school
    window.deleteMedicalSchool = function(id) {
        if (confirm('Are you sure you want to delete this medical school?')) {
            fetch(`/wacs_system/public/?url=candidates/delete-medical-school&id=${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById(`medical-school-${id}`).remove();
                    responseDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
                    
                    // Check if list is empty
                    const list = document.getElementById('medical-school-list');
                    if (list.children.length === 0) {
                        list.innerHTML = '<div class="text-muted" id="no-medical-schools">No medical schools added yet.</div>';
                    }
                } else {
                    responseDiv.innerHTML = '<div class="alert alert-danger">' + result.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseDiv.innerHTML = '<div class="alert alert-danger">Failed to delete medical school.</div>';
            });
        }
    };
})();
</script>

