<?php
// Assumes $candidate and relevant datasets are already fetched
<script src="/wacs_system/public/js/profile.js"></script>
?>
<div class="container-fluid">
  <div class="row">
    <!-- LEFT COLUMN -->
    <div class="col-md-6">

      <!-- PASSPORT UPLOAD -->
      <div class="text-center mb-4">
        <form action="/wacs_system/public/?url=candidates/upload-passport&id=<?= $candidate['id'] ?>" method="POST" enctype="multipart/form-data" id="passportUploadForm">
          <div id="passport-dropzone"
               class="border rounded d-flex align-items-center justify-content-center"
               style="width: 150px; height: 150px; cursor: pointer; position: relative; margin: 0 auto;"
               title="Click to select passport image">
            <img id="passportPreview"
                 src="<?= !empty($candidate['passport']) ? htmlspecialchars($candidate['passport']) : 'https://via.placeholder.com/100?text=Click+to+Upload' ?>"
                 alt="Passport"
                 style="max-width: 100px; max-height: 100px; object-fit: contain; pointer-events: none;">
            <input type="file" name="passport" id="passportInput" accept="image/*" style="display: none;">
          </div>
          <small class="text-muted d-block mt-1">Click image area or drag & drop</small>
          <button type="submit" class="btn btn-sm btn-primary mt-2" id="uploadButton">Upload</button>
        </form>
        <div id="uploadResponse" class="mt-2"></div>
      </div>

      <!-- MEDICAL SCHOOL ATTENDED -->
      <h5>Medical School Attended</h5>
      <button class="btn btn-success btn-sm mb-2" onclick="document.getElementById('medSchoolForm').classList.toggle('d-none')">Add New</button>
      <form action="/wacs_system/public/?url=candidates/save-medical-school" method="POST" class="d-none" id="medSchoolForm">
        <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
        <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Medical School" required></div>
        <div class="mb-2"><input type="date" name="start_date" class="form-control" required></div>
        <div class="mb-2"><input type="date" name="end_date" class="form-control" required></div>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
      </form>

      <?php if (!empty($medical_school)): ?>
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>School</th><th>Start</th><th>End</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($medical_school as $school): ?>
              <tr>
                <td><?= htmlspecialchars($school['name']) ?></td>
                <td><?= htmlspecialchars($school['start_date']) ?></td>
                <td><?= htmlspecialchars($school['end_date']) ?></td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSchoolModal<?= $school['id'] ?>">Edit</button>
                  <form method="POST" action="/wacs_system/public/?url=candidates/delete-medical-school" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $school['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this entry?')">Delete</button>
                  </form>
                </td>
              </tr>

              <!-- EDIT MODAL -->
              <div class="modal fade" id="editSchoolModal<?= $school['id'] ?>" tabindex="-1" aria-labelledby="editSchoolLabel<?= $school['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="POST" action="/wacs_system/public/?url=candidates/update-medical-school">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editSchoolLabel<?= $school['id'] ?>">Edit Medical School</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $school['id'] ?>">
                        <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
                        <div class="mb-2"><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($school['name']) ?>" required></div>
                        <div class="mb-2"><input type="date" name="start_date" class="form-control" value="<?= $school['start_date'] ?>" required></div>
                        <div class="mb-2"><input type="date" name="end_date" class="form-control" value="<?= $school['end_date'] ?>" required></div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- END EDIT MODAL -->

            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>

      <!-- INSTITUTION SECTION -->
      <h5 class="mt-4">Institution Attended</h5>
      <button class="btn btn-success btn-sm mb-2" onclick="document.getElementById('institutionForm').classList.toggle('d-none')">Add New Institution</button>
      <form action="/wacs_system/public/?url=candidates/save-institution" method="POST" class="d-none border p-3 rounded" id="institutionForm">
        <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
        <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Institution Name" required></div>
        <div class="mb-2"><input type="text" name="degree" class="form-control" placeholder="Degree Awarded (e.g., MBBS)" required></div>
        <div class="mb-2"><label for="institution_type">Type of Institution:</label><input type="text" name="institution_type" id="institution_type" class="form-control" required></div>
        <div class="mb-2"><input type="date" name="start_date" class="form-control" required></div>
        <div class="mb-2"><input type="date" name="end_date" class="form-control" required></div>
        <div class="mb-2"><label><input type="checkbox" name="status" value="1" checked> Active</label></div>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
      </form>

      <?php
        $institutions = $this->pdo->prepare("SELECT name FROM institution WHERE candidate_id = ?");
        $institutions->execute([$candidate['id']]);
        $institutions = $institutions->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if (!empty($institutions)): ?>
        <div class="mt-3">
          <?php foreach ($institutions as $inst): ?>
            <div><?= htmlspecialchars($inst['name']) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- POSTGRADUATE TRAINING -->
      <h5 class="mt-4">Postgraduate Training Attended</h5>
      <button class="btn btn-success btn-sm mb-2" onclick="document.getElementById('postgradForm').classList.toggle('d-none')">Add New</button>
      <form action="/wacs_system/public/?url=candidates/save-postgraduate-training" method="POST" class="d-none" id="postgradForm">
        <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
        <div class="mb-2"><input type="text" name="post_training_school" class="form-control" placeholder="Post Training School" required></div>
        <div class="mb-2"><input type="text" name="degree" class="form-control" placeholder="Degree" required></div>
        <div class="mb-2"><input type="date" name="start_date" class="form-control" required></div>
        <div class="mb-2"><input type="date" name="end_date" class="form-control" required></div>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
      </form>
    </div>

    <!-- RIGHT COLUMN: CANDIDATE PERSONAL DETAILS -->
    <div class="col-md-6">
      <h5>Update Candidate Details</h5>
      <form action="/wacs_system/public/?url=candidates/update-details&id=<?= $candidate['id'] ?>" method="POST">
        <div class="row">
          <?php
          $fields = [
            'registration_number', 'cert_number', 'surname', 'other_name', 'maiden_name', 'change_of_name',
            'email', 'phone', 'address', 'postal_address', 'dob', 'gender', 'nationality',
            'country', 'faculty_id', 'sub_speciality', 'full_registration_date', 'entry_mode',
            'nysc_discharge_or_exemption', 'prefered_exam_center', 'accredited_training_program',
            'post_registration_appointment', 'fellowship_type'
          ];

          foreach ($fields as $field) {
            echo '<div class="col-md-6 mb-2">';
            if ($field === 'gender') {
              echo '<select name="gender" class="form-control">';
              echo '<option value="Male" ' . ($candidate['gender'] == 'Male' ? 'selected' : '') . '>Male</option>';
              echo '<option value="Female" ' . ($candidate['gender'] == 'Female' ? 'selected' : '') . '>Female</option>';
              echo '</select>';
            } elseif ($field === 'fellowship_type') {
              echo '<select name="fellowship_type" class="form-control">';
              echo '<option value="">Select Fellowship Type</option>';
              echo '<option value="Part I" ' . ($candidate['fellowship_type'] == 'Part I' ? 'selected' : '') . '>Part I</option>';
              echo '<option value="Part II" ' . ($candidate['fellowship_type'] == 'Part II' ? 'selected' : '') . '>Part II</option>';
              echo '</select>';
            } else {
              $type = strpos($field, 'date') !== false || $field === 'dob' ? 'date' : 'text';
              echo '<input type="' . $type . '" name="' . $field . '" class="form-control" value="' . htmlspecialchars($candidate[$field]) . '" placeholder="' . ucwords(str_replace('_', ' ', $field)) . '">';
            }
            echo '</div>';
          }
          ?>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update Details</button>
      </form>
    </div>
  </div>
</div>

<!-- JAVASCRIPT HANDLERS -->
<script>
<?php include 'passport_upload_handler.js'; ?>
</script>

