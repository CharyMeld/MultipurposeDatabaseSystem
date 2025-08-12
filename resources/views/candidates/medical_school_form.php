<div class="card shadow-sm">
  <div class="card-body position-relative">
    <h5 class="card-title">Medical School Attended</h5>

    <!-- Medical School List -->
    <div id="medical-school-list" class="mb-1">
      <?php if (!empty($medicalSchools)): ?>
        <?php foreach ($medicalSchools as $school): ?>
          <div class="card mb-1" id="medical-school-<?= $school['id'] ?>">
            <div class="card-body py-1 px-2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong><?= htmlspecialchars($school['medical_school']) ?></strong><br>
                  <small class="text-muted">
                    <?= date('M Y', strtotime($school['start_date'])) ?> -
                    <?= date('M Y', strtotime($school['end_date'])) ?>
                  </small>
                </div>
                <div>
                  <button class="btn btn-sm btn-outline-primary me-1"
                          onclick="editMedicalSchool(<?= $school['id'] ?>)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger"
                          onclick="deleteMedicalSchool(<?= $school['id'] ?>)">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-muted small" id="no-medical-schools">No medical schools added yet.</div>
      <?php endif; ?>
    </div>

    <!-- Add Button -->
    <div class="text-end mb-2">
      <button class="btn btn-success btn-sm" id="add-medical-school-btn">+ Add Medical School</button>
    </div>

    <!-- Form -->
    <div id="medical-school-form-container" style="display:none;">
      <div class="card mb-2">
        <div class="card-body p-2">
          <h6 class="card-title mb-2">Add Medical School</h6>
          <form id="medicalSchoolForm">
            <input type="hidden" name="candidate_id" value="<?= $candidateId ?>">
            <input type="hidden" name="medical_school_id" id="medical_school_id" value="">

            <div class="mb-1">
              <label for="medical_school" class="form-label mb-1 small">Medical School</label>
              <input type="text" name="medical_school" id="medical_school" class="form-control form-control-sm" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-1">
                <label for="start_date" class="form-label mb-1 small">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" required>
              </div>
              <div class="col-md-6 mb-1">
                <label for="end_date" class="form-label mb-1 small">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" required>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm">Save</button>
              <button type="button" class="btn btn-secondary btn-sm" id="cancel-medical-school">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Response -->
    <div id="medical-school-response" class="mt-1"></div>
  </div>
</div>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const addBtn = document.getElementById("add-medical-school-btn");
  const formContainer = document.getElementById("medical-school-form-container");
  const cancelBtn = document.getElementById("cancel-medical-school");
  const form = document.getElementById("medicalSchoolForm");
  const responseDiv = document.getElementById("medical-school-response");

  // Show form
  addBtn.addEventListener("click", function () {
    formContainer.style.display = "block";
    addBtn.style.display = "none";
  });

  // Cancel form
  cancelBtn.addEventListener("click", function () {
    formContainer.style.display = "none";
    addBtn.style.display = "inline-block";
    form.reset();
    responseDiv.innerHTML = "";
  });

  // Submit form
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    const isEdit = document.getElementById("medical_school_id").value !== "";
    const url = isEdit
      ? "/wacs_system/public/?url=candidates/update-medical-school"
      : "/wacs_system/public/?url=candidates/save-medical-school";

    fetch(url, {
      method: "POST",
      body: formData,
      headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
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
});
</script>

<style>
/* Reduce spacing globally for this block */
#medical-school-list .card { margin-bottom: 5px; }
#medical-school-form-container .card { margin-bottom: 5px; }
#medical-school-response .alert { margin: 5px 0; }
</style>
