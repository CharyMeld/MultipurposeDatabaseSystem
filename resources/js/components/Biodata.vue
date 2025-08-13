<template>
  <div class="container-fluid">
    <!-- SERVER MESSAGE DISPLAY -->
    <div v-if="serverMessage.text"
         :class="['alert', serverMessage.type === 'success' ? 'alert-success' : 'alert-danger']"
         role="alert">
      {{ serverMessage.text }}
    </div>

    <div class="row">
      <!-- LEFT COLUMN -->
      <div class="col-md-6">
        <!-- PASSPORT UPLOAD -->
        
        <div class="text-center mb-4">
          <form id="passportUploadForm" @submit.prevent="submitPassport" enctype="multipart/form-data">
            <div
              id="passport-dropzone"
              class="border rounded d-flex align-items-center justify-content-center"
              style="width: 150px; height: 150px; cursor: pointer; position: relative; margin: 0 auto;"
              @click.prevent="onPassportClick"
              @dragover.prevent
              @dragenter.prevent
              @drop.prevent="onPassportDrop"
              title="Click or drag & drop passport image"
            >
              <img
                :src="passportPreview"
                alt="Passport"
                style="max-width: 100px; max-height: 100px; object-fit: contain; pointer-events: none;"
              />
              <input
                type="file"
                accept="image/*"
                style="display: none"
                ref="passportInput"
                @change="onPassportChange"
              />
            </div>
            <small class="text-muted d-block mt-1">Click the image or drag & drop your passport here</small>
            <button type="submit" class="btn btn-sm btn-primary mt-2">Upload</button>
          </form>
          <div id="uploadResponse" class="mt-2"></div>
        </div>


        <!-- MEDICAL SCHOOL ATTENDED -->
        <h5>Medical School Attended</h5>
        <button class="btn btn-success btn-sm mb-2" type="button" @click="showMedSchoolForm = !showMedSchoolForm">Add New</button>

        <form v-show="showMedSchoolForm" id="medSchoolForm" @submit.prevent="saveMedicalSchool">
          <input type="hidden" name="candidate_id" :value="form.id" />
          <div class="mb-2">
            <input type="text" name="name" class="form-control" placeholder="Medical School" required />
          </div>
          <div class="mb-2">
            <input type="date" name="start_date" class="form-control" required />
          </div>
          <div class="mb-2">
            <input type="date" name="end_date" class="form-control" />
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </form>

        <table v-if="medicalSchools.length" class="table table-bordered table-sm mt-2">
          <tbody>
            <tr v-for="school in medicalSchools" :key="school.id">
              <td>{{ school.name }}</td>
              <td>{{ school.start_date }}</td>
              <td>{{ school.end_date }}</td>
              <td>
                <button class="btn btn-warning btn-sm" type="button" @click="openEditMedSchoolModal(school)">Edit</button>
                <button class="btn btn-danger btn-sm" type="button" @click="deleteMedicalSchool(school.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- SINGLE EDIT MODAL -->
        <div class="modal fade" id="editSchoolModal" tabindex="-1" aria-labelledby="editSchoolLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form @submit.prevent="updateMedicalSchool">
                <div class="modal-header">
                  <h5 class="modal-title" id="editSchoolLabel">Edit Medical School</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" v-model="editMedSchoolData.id" />
                  <input type="hidden" name="candidate_id" :value="form.id" />
                  <div class="mb-2">
                    <label>School Name:</label>
                    <input type="text" name="name" class="form-control" v-model="editMedSchoolData.name" required />
                  </div>
                  <div class="mb-2">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" class="form-control" v-model="editMedSchoolData.start_date" required />
                  </div>
                  <div class="mb-2">
                    <label>End Date:</label>
                    <input type="date" name="end_date" class="form-control" v-model="editMedSchoolData.end_date" />
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- INSTITUTION SECTION -->
        <h5 class="mt-4">Institution Attended</h5>
        <button class="btn btn-success btn-sm mb-2" type="button" @click="showInstitutionForm = !showInstitutionForm">Add New Institution</button>

        <form v-show="showInstitutionForm" class="border p-3 rounded" @submit.prevent="saveInstitution">
          <input type="hidden" name="candidate_id" :value="form.id" />
          <div class="mb-2">
            <input type="text" name="name" class="form-control" placeholder="Institution Name" required />
          </div>
          <div class="mb-2">
            <input type="text" name="degree" class="form-control" placeholder="Degree Awarded (e.g., MBBS)" required />
          </div>
          <div class="mb-2">
            <label for="institution_type">Type of Institution:</label>
            <input type="text" name="institution_type" id="institution_type" class="form-control" required />
          </div>
          <div class="mb-2">
            <input type="date" name="start_date" class="form-control" required />
          </div>
          <div class="mb-2">
            <input type="date" name="end_date" class="form-control" />
          </div>
          <div class="mb-2">
            <label><input type="checkbox" name="status" value="1" checked /> Active</label>
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </form>

        <div v-if="institutions.length" class="mt-3">
          <div v-for="inst in institutions" :key="inst.id">{{ inst.name }}</div>
        </div>

        <!-- POSTGRADUATE TRAINING -->
        <h5 class="mt-4">Postgraduate Training Attended</h5>
        <button class="btn btn-success btn-sm mb-2" type="button" @click="showPostgradForm = !showPostgradForm">Add New</button>

        <form v-show="showPostgradForm" id="postgradForm" @submit.prevent="savePostgrad">
          <input type="hidden" name="candidate_id" :value="form.id" />
          <div class="mb-2">
            <input type="text" name="name" class="form-control" placeholder="Post Training School" required />
          </div>
          <div class="mb-2">
            <input type="text" name="degree" class="form-control" placeholder="Degree" required />
          </div>
          <div class="mb-2">
            <input type="date" name="start_date" class="form-control" required />
          </div>
          <div class="mb-2">
            <input type="date" name="end_date" class="form-control" />
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </form>
      </div>

      
      <!-- RIGHT COLUMN: CANDIDATE PERSONAL DETAILS -->
<div class="col-md-6">
  <h5>Update Candidate Details</h5>

  <!-- Edit button -->
  <button type="button" class="btn btn-secondary mb-2" @click="toggleEdit">
    {{ isEditable ? 'Cancel Edit' : 'Edit' }}
  </button>

  <form @submit.prevent="updateCandidateDetails">
    <div class="row">
      <div v-for="field in fields" :key="field" class="col-md-6 mb-2">

        <!-- Gender -->
        <select v-if="field === 'gender'" name="gender" class="form-control"
          v-model="form.gender"
          :disabled="!isEditable">
          <option value="">Select</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>

        <!-- Fellowship Type -->
        <select v-else-if="field === 'fellowship_type'" name="fellowship_type" class="form-control"
          v-model="form.fellowship_type"
          :disabled="!isEditable">
          <option value="">Select Fellowship Type</option>
          <option value="Part I">Part I</option>
          <option value="Part II">Part II</option>
        </select>

        <!-- Other fields -->
        <input v-else
          :type="field.includes('date') || field === 'dob' ? 'date' : 'text'"
          :name="field"
          class="form-control"
          :placeholder="formatLabel(field)"
          v-model="form[field]"
          :readonly="!isEditable"
        />
      </div>
    </div>

    <!-- Update Button -->
    <button type="submit" class="btn btn-primary mt-2">Update Details</button>
  </form>
</div>
</div>
</div>
</template>



<script setup>
import { ref, reactive, onMounted, watch } from 'vue';

// Props: accept candidate and lists from parent/profile mount
const props = defineProps({
  candidate: { type: Object, default: () => ({}) },
  medicalSchools: { type: Array, default: () => [] },
  institutions: { type: Array, default: () => [] },
  postgraduates: { type: Array, default: () => [] },
  faculty_name: { type: String, default: '' },
  sub_speciality: { type: String, default: '' }
});

// CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Default candidate shape
const defaultCandidate = {
  id: '',
  registration_number: '',
  cert_number: null,
  surname: '',
  other_name: '',
  maiden_name: '',
  change_of_name: '',
  email: '',
  phone: '',
  address: '',
  postal_address: '',
  dob: '',
  gender: '',
  nationality: '',
  fellowship_type: '',
  country: '',
  faculty_id: '',
  sub_speciality: '',
  full_registration_date: '',
  entry_mode: '',
  nysc_discharge_or_exemption: '',
  prefered_exam_center: '',
  accredited_training_program: '',
  post_registration_appointment: '',
  created_at: '',
  updated_at: '',
  passport: '',
  medicalSchools: [],
  institutions: [],
  postgraduateTrainings: []
};

// Merge defaults with provided candidate
const candidateData = { ...defaultCandidate, ...(props.candidate || {}) };

// UI state
const serverMessage = ref({ type: '', text: '' });
const fields = [
  'registration_number', 'cert_number', 'surname', 'other_name', 'maiden_name', 'change_of_name',
  'email', 'phone', 'address', 'postal_address', 'dob', 'gender', 'nationality',
  'country', 'faculty_id', 'sub_speciality', 'full_registration_date', 'entry_mode',
  'nysc_discharge_or_exemption', 'prefered_exam_center', 'accredited_training_program',
  'post_registration_appointment', 'fellowship_type'
];

// Reactive form and toggles
const form = reactive({ ...candidateData });
const showMedSchoolForm = ref(false);
const showInstitutionForm = ref(false);
const showPostgradForm = ref(false);

// passport preview and file refs
const passportPreview = ref(
  form.passport && form.passport.toString().trim()
    ? (form.passport.toString().startsWith('http') ? form.passport : `/storage/passports/${form.passport}`)
    : '/assets/images/placeholder.png'
);
const passportFile = ref(null);
const passportInput = ref(null);

// Lists (use props if passed, otherwise use form data)
const medicalSchools = ref(props.medicalSchools.length ? props.medicalSchools : (form.medicalSchools || []));
const institutions = ref(props.institutions.length ? props.institutions : (form.institutions || []));
const postgrads = ref(props.postgraduates.length ? props.postgraduates : (form.postgraduateTrainings || []));

// Edit modal state
const editMedSchoolData = reactive({ id: null, name: '', start_date: '', end_date: '' });
let bsModalInstance = null;

// Track candidate edit mode
const isEditable = ref(false);

// Helpers
function formatLabel(field) {
  return field.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase());
}
function showServerMessage(type, text) {
  serverMessage.value = { type, text };
  setTimeout(() => serverMessage.value = { type: '', text: '' }, 5000);
}

// Passport logic
function onPassportClick() {
  passportInput.value?.click();
}
function onPassportChange(e) {
  const file = e.target.files?.[0];
  if (!file) return;
  passportFile.value = file;
  const reader = new FileReader();
  reader.onload = (ev) => passportPreview.value = ev.target.result;
  reader.readAsDataURL(file);
}
async function submitPassport() {
  if (!passportFile.value) {
    showServerMessage('error', 'Please choose a file first.');
    return;
  }
  const fd = new FormData();
  fd.append('passport', passportFile.value);
  fd.append('candidate_id', form.id);

  try {
    const res = await fetch('/candidates/profile/upload-passport', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: fd
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Upload failed');

    showServerMessage('success', 'Passport uploaded successfully.');
    if (data.passport_url) {
      passportPreview.value = data.passport_url;
      form.passport = data.passport_filename || data.passport_url.split('/').pop();
    }
  } catch (err) {
    console.error(err);
    showServerMessage('error', err.message || err);
  }
}

// Medical School CRUD
function openEditMedSchoolModal(school) {
  editMedSchoolData.id = school.id;
  editMedSchoolData.name = school.name;
  editMedSchoolData.start_date = school.start_date;
  editMedSchoolData.end_date = school.end_date;
  bsModalInstance?.show();
}

async function saveMedicalSchool(e) {
  e.preventDefault();
  const fd = new FormData(e.target);
  const baseProfileUrl = `/candidates/${form.id}/profile`;
  try {
    const res = await fetch(`${baseProfileUrl}/save-medical-school`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: fd
    });
    const newSchool = await res.json();
    if (!res.ok) throw new Error(newSchool.message || 'Save failed');

    medicalSchools.value.push(newSchool.data || newSchool);
    showServerMessage('success', 'Medical school saved.');
    e.target.reset();
    showMedSchoolForm.value = false;
  } catch (err) {
    console.error(err);
    showServerMessage('error', err.message || err);
  }
}

async function updateMedicalSchool(e) {
  e.preventDefault();
  const fd = new FormData(e.target);
  const baseProfileUrl = `/candidates/${form.id}/profile`;
  try {
    const res = await fetch(`${baseProfileUrl}/update-medical-school`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: fd
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Update failed');

    const idx = medicalSchools.value.findIndex(s => s.id === editMedSchoolData.id);
    if (idx !== -1) medicalSchools.value[idx] = { ...medicalSchools.value[idx], ...editMedSchoolData };
    showServerMessage('success', 'Medical school updated.');
    bsModalInstance?.hide();
  } catch (err) {
    console.error(err);
    showServerMessage('error', err.message || err);
  }
}

async function deleteMedicalSchool(id) {
  if (!confirm('Are you sure you want to delete this entry?')) return;
  const fd = new FormData();
  fd.append('id', id);
  const baseProfileUrl = `/candidates/${form.id}/profile`;
  try {
    const res = await fetch(`${baseProfileUrl}/delete-medical-school`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: fd
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Delete failed');
    medicalSchools.value = medicalSchools.value.filter(s => s.id !== id);
    showServerMessage('success', 'Medical school deleted.');
  } catch (err) {
    console.error(err);
    showServerMessage('error', err.message || err);
  }
}

// Institution & Postgrad

async function saveInstitution(e) {
  const fd = new FormData(e.target);
  const candidateId = form.id; // make sure you have candidate ID
  try {
    const res = await fetch(`/candidates/${candidateId}/profile/save-institution`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: fd
    });
    const newInstitution = await res.json();
    if (!res.ok) throw new Error(newInstitution.message || 'Save failed');
    institutions.value.push(newInstitution.data || newInstitution);
    showServerMessage('success', 'Institution saved.');
    e.target.reset();
    showInstitutionForm.value = false;
  } catch (err) {
    console.error(err);
    showServerMessage('error', err.message || err);
  }
}


    async function savePostgrad(e) {
      e.preventDefault();
      const fd = new FormData(e.target);

      // Make sure form.id is the candidate ID
      const candidateId = form.id;

      try {
        const res = await fetch(`/candidates/${candidateId}/profile/save-postgraduate-training`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
          body: fd
        });

        const newPostgrad = await res.json();
        if (!res.ok) throw new Error(newPostgrad.message || 'Save failed');

        postgrads.value.push(newPostgrad.data || newPostgrad);
        showServerMessage('success', 'Postgraduate training saved.');
        e.target.reset();
        showPostgradForm.value = false;
      } catch (err) {
        console.error(err);
        showServerMessage('error', err.message || err);
      }
    }


    // Toggle candidate edit mode
    function toggleEdit() {
      isEditable.value = !isEditable.value;
    }

// Update candidate details
async function updateCandidateDetails() {
  if (!isEditable.value) {
    showServerMessage('error', 'Please click "Edit" before updating.');
    return;
  }

  try {
    const res = await fetch(`/candidates/${form.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify(form)
    });

    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Update failed');

    showServerMessage('success', 'Candidate details updated successfully.');

    if (data.candidate) Object.assign(form, data.candidate);

    // Disable edit mode again
    isEditable.value = false;
  } catch (err) {
    console.error(err);
    showServerMessage('error', err.message || err);
  }
}

// Lifecycle
onMounted(() => {
  const modalEl = document.getElementById('editSchoolModal');
  if (modalEl && window.bootstrap) {
    bsModalInstance = new window.bootstrap.Modal(modalEl);
  }
});

// Watch for prop updates
watch(() => props.candidate, (newVal) => {
  if (newVal && Object.keys(newVal).length) {
    Object.assign(form, { ...defaultCandidate, ...newVal });
    passportPreview.value = form.passport
      ? (form.passport.toString().startsWith('http') ? form.passport : `/storage/passports/${form.passport}`)
      : '/assets/images/placeholder.png';

    if (Array.isArray(props.medicalSchools) && props.medicalSchools.length) medicalSchools.value = props.medicalSchools;
    if (Array.isArray(props.institutions) && props.institutions.length) institutions.value = props.institutions;
    if (Array.isArray(props.postgraduates) && props.postgraduates.length) postgrads.value = props.postgraduates;
  }
});
</script>

<style scoped>
#passport-dropzone img {
  display: block;
}

/* optional: highlight editable fields */
input[readonly], select[disabled] {
  background-color: #f8f9fa;
}
input:not([readonly]), select:not([disabled]) {
  background-color: #fff8dc; /* subtle highlight for editable fields */
}
</style>

