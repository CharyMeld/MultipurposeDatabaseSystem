<h5 class="mt-4">Institution Attended</h5>

<!-- Institution List -->
<div id="institution-list" class="mb-1">
  <?php if (!empty($institutions)): ?>
    <?php foreach ($institutions as $institution): ?>
      <div class="card mb-1" id="institution-<?= $institution['id'] ?>">
        <div class="card-body py-1 px-2">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <strong><?= htmlspecialchars($institution['institution']) ?></strong><br>
              <span class="text-primary"><?= htmlspecialchars($institution['degree']) ?></span><br>
              <small class="text-muted">
                <?= date('M Y', strtotime($institution['start_date'])) ?> -
                <?= date('M Y', strtotime($institution['end_date'])) ?>
              </small>
            </div>
            <div>
              <button class="btn btn-sm btn-outline-primary me-1" onclick="editInstitution(<?= $institution['id'] ?>)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteInstitution(<?= $institution['id'] ?>)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-muted small" id="no-institutions">No institutions added yet.</div>
  <?php endif; ?>
</div>

<!-- Add Button -->
<div class="text-end mb-2">
  <button class="btn btn-success btn-sm" id="add-institution-btn">+ Add Institution</button>
</div>

<!-- Form Container -->
<div id="institution-form-container" style="display: none;">
  <div class="card mb-2">
    <div class="card-body p-2">
      <h6 class="card-title mb-2">Add Institution</h6>
      <form id="institutionForm">
        <input type="hidden" name="candidate_id" value="<?= $candidateId ?>">
        <input type="hidden" name="institution_id" id="institution_id">

        <div class="mb-1">
          <label for="institution" class="form-label small mb-1">Institution</label>
          <input type="text" name="institution" id="institution" class="form-control form-control-sm" required>
        </div>

        <div class="mb-1">
          <label for="degree" class="form-label small mb-1">Degree</label>
          <input type="text" name="degree" id="degree" class="form-control form-control-sm" required>
        </div>

        <div class="row">
          <div class="col-md-6 mb-1">
            <label for="inst_start_date" class="form-label small mb-1">Start Date</label>
            <input type="date" name="start_date" id="inst_start_date" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-6 mb-1">
            <label for="inst_end_date" class="form-label small mb-1">End Date</label>
            <input type="date" name="end_date" id="inst_end_date" class="form-control form-control-sm" required>
          </div>
        </div>

        <div class="d-flex gap-2 mt-1">
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
          <button type="button" class="btn btn-secondary btn-sm" id="cancel-institution">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Response -->
<div id="institution-response" class="mt-1"></div>

<script>
(function() {
  const addBtn = document.getElementById('add-institution-btn');
  const formContainer = document.getElementById('institution-form-container');
  const form = document.getElementById('institutionForm');
  const cancelBtn = document.getElementById('cancel-institution');
  const responseDiv = document.getElementById('institution-response');

  // Show form
  addBtn.addEventListener('click', function() {
    formContainer.style.display = 'block';
    addBtn.style.display = 'none';
    clearForm();
  });

  // Cancel form
  cancelBtn.addEventListener('click', function() {
    formContainer.style.display = 'none';
    addBtn.style.display = 'inline-block';
    clearForm();
  });

  // Clear and reset
  function clearForm() {
    form.reset();
    document.getElementById('institution_id').value = '';
    document.querySelector('#institutionForm h6').textContent = 'Add Institution';
    responseDiv.innerHTML = '';
  }

  // Submit form
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    const isEdit = document.getElementById('institution_id').value !== '';
    const url = isEdit
      ? '/wacs_system/public/?url=candidates/update-institution'
      : '/wacs_system/public/?url=candidates/save-institution';

    fetch(url, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        responseDiv.innerHTML = `<div class="alert alert-success py-1 small">${result.message}</div>`;
        setTimeout(() => location.reload(), 1000);
      } else {
        responseDiv.innerHTML = `<div class="alert alert-danger py-1 small">${result.message}</div>`;
      }
    })
    .catch(() => {
      responseDiv.innerHTML = `<div class="alert alert-danger py-1 small">Error saving data.</div>`;
    });
  });

  // Edit
  window.editInstitution = function(id) {
    fetch(`/wacs_system/public/?url=candidates/get-institution&id=${id}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const d = data.institution;
        document.getElementById('institution_id').value = d.id;
        document.getElementById('institution').value = d.institution;
        document.getElementById('degree').value = d.degree;
        document.getElementById('inst_start_date').value = d.start_date;
        document.getElementById('inst_end_date').value = d.end_date;

        formContainer.style.display = 'block';
        addBtn.style.display = 'none';
        document.querySelector('#institutionForm h6').textContent = 'Edit Institution';
      }
    })
    .catch(() => {
      responseDiv.innerHTML = `<div class="alert alert-danger py-1 small">Failed to load institution data.</div>`;
    });
  };

  // Delete
  window.deleteInstitution = function(id) {
    if (!confirm('Delete this institution?')) return;
    fetch(`/wacs_system/public/?url=candidates/delete-institution&id=${id}`, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(result => {
      if (result.success) {
        document.getElementById(`institution-${id}`).remove();
        responseDiv.innerHTML = `<div class="alert alert-success py-1 small">${result.message}</div>`;
        if (document.querySelectorAll('#institution-list .card').length === 0) {
          document.getElementById('institution-list').innerHTML =
            '<div class="text-muted small" id="no-institutions">No institutions added yet.</div>';
        }
      } else {
        responseDiv.innerHTML = `<div class="alert alert-danger py-1 small">${result.message}</div>`;
      }
    })
    .catch(() => {
      responseDiv.innerHTML = `<div class="alert alert-danger py-1 small">Error deleting record.</div>`;
    });
  };
})();
</script>
