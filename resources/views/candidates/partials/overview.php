<?php
// Assume $candidate, $medicalSchools, $institutions, and $postgraduates are passed from the controller
?>

<!-- PASSPORT -->
<div class="text-center mb-4">
  <div class="border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; overflow: hidden; background-color: #f8f9fa;">
    <img src="<?= !empty($candidate['passport']) ? htmlspecialchars($candidate['passport']) : 'https://via.placeholder.com/100' ?>"
         alt="Passport"
         style="width: 100%; height: 100%; object-fit: cover;">
  </div>
</div>

<!-- CANDIDATE DETAILS -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white">Candidate Details</div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6 mb-2"><strong>Registration No:</strong> <?= htmlspecialchars($candidate['registration_number'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Surname:</strong> <?= htmlspecialchars($candidate['surname'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Other Name:</strong> <?= htmlspecialchars($candidate['other_name'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Maiden Name:</strong> <?= htmlspecialchars($candidate['maiden_name'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Entry Mode:</strong> <?= htmlspecialchars($candidate['entry_mode'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Country:</strong> <?= htmlspecialchars($candidate['country'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> <?= htmlspecialchars($candidate['dob'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Gender:</strong> <?= htmlspecialchars($candidate['gender'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Nationality:</strong> <?= htmlspecialchars($candidate['nationality'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Fellowship Type:</strong> <?= htmlspecialchars($candidate['fellowship_type'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Faculty:</strong> <?= htmlspecialchars($candidate['faculty_id'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Sub Speciality:</strong> <?= htmlspecialchars($candidate['sub_speciality'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>NYSC Discharge/Exemption:</strong> <?= htmlspecialchars($candidate['nysc_discharge_or_exemption'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Accredited Training Program:</strong> <?= htmlspecialchars($candidate['accredited_training_program'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Email:</strong> <?= htmlspecialchars($candidate['email'] ?? '') ?></div>
      <div class="col-md-6 mb-2"><strong>Phone:</strong> <?= htmlspecialchars($candidate['phone'] ?? '') ?></div>
    </div>
  </div>
</div>


<!-- MEDICAL SCHOOL -->
<?php if (!empty($medicalSchools)) : ?>
<div class="card shadow-sm mb-4">
  <div class="card-header bg-success text-white">Medical School Attended</div>
  <div class="card-body">
    <ul class="list-group">
      <?php foreach ($medicalSchools as $school) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($school['name']) ?>
          <div>
            <a href="#" class="btn btn-sm btn-outline-primary me-1">Edit</a>
            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<!-- INSTITUTIONS ATTENDED -->
<?php if (!empty($institutions)) : ?>
<div class="card shadow-sm mb-4">
  <div class="card-header bg-info text-white">Institution(s) Attended</div>
  <div class="card-body">
    <ul class="list-group">
      <?php foreach ($institutions as $institution) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($institution['name']) ?>
          <div>
            <a href="#" class="btn btn-sm btn-outline-primary me-1">Edit</a>
            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<!-- POSTGRADUATE TRAINING -->
<?php if (!empty($postgraduates)) : ?>
<div class="card shadow-sm mb-4">
  <div class="card-header bg-warning text-dark">Postgraduate Training</div>
  <div class="card-body">
    <ul class="list-group">
      <?php foreach ($postgraduates as $pg) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($pg['field']) ?>
          <div>
            <a href="#" class="btn btn-sm btn-outline-primary me-1">Edit</a>
            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

