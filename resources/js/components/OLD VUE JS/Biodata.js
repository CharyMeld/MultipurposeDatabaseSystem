// resources/js/components/Biodata.js
import { ref, reactive, onMounted } from 'vue';

export const Biodata = {
  props: ['candidate', 'faculty_name', 'sub_speciality'],

  setup(props) {
    // CSRF token (expects <meta name="csrf-token" content="..."> in your layout)
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Fields list (same as your original)
    const fields = [
      'registration_number', 'cert_number', 'surname', 'other_name', 'maiden_name', 'change_of_name',
      'email', 'phone', 'address', 'postal_address', 'dob', 'gender', 'nationality',
      'country', 'faculty_id', 'sub_speciality', 'full_registration_date', 'entry_mode',
      'nysc_discharge_or_exemption', 'prefered_exam_center', 'accredited_training_program',
      'post_registration_appointment', 'fellowship_type'
    ];

    // Reactive form prefilled from prop
    const form = reactive({
      id: props.candidate?.id || '',
      registration_number: props.candidate?.registration_number || '',
      cert_number: props.candidate?.cert_number || '',
      surname: props.candidate?.surname || '',
      other_name: props.candidate?.other_name || '',
      maiden_name: props.candidate?.maiden_name || '',
      change_of_name: props.candidate?.change_of_name || '',
      email: props.candidate?.email || '',
      phone: props.candidate?.phone || '',
      address: props.candidate?.address || '',
      postal_address: props.candidate?.postal_address || '',
      dob: props.candidate?.dob || '',
      gender: props.candidate?.gender || '',
      nationality: props.candidate?.nationality || '',
      country: props.candidate?.country || '',
      faculty_id: props.candidate?.faculty_id || '',
      sub_speciality: props.candidate?.sub_speciality || '',
      full_registration_date: props.candidate?.full_registration_date || '',
      entry_mode: props.candidate?.entry_mode || '',
      nysc_discharge_or_exemption: props.candidate?.nysc_discharge_or_exemption || '',
      prefered_exam_center: props.candidate?.prefered_exam_center || '',
      accredited_training_program: props.candidate?.accredited_training_program || '',
      post_registration_appointment: props.candidate?.post_registration_appointment || '',
      fellowship_type: props.candidate?.fellowship_type || '',
    });

    // Toggles
    const showMedSchoolForm = ref(false);
    const showInstitutionForm = ref(false);
    const showPostgradForm = ref(false);

    // Passport preview + file
    const passportPreview = ref(props.candidate?.passport || 'https://via.placeholder.com/100?text=Click+to+Upload');
    const passportFile = ref(null);

    function onPassportClick() {
      const el = document.getElementById('passportInput');
      if (el) el.click();
    }
    function onPassportChange(e) {
      const file = e.target.files?.[0];
      if (!file) return;
      passportFile.value = file;
      const reader = new FileReader();
      reader.onload = (ev) => (passportPreview.value = ev.target.result);
      reader.readAsDataURL(file);
    }

    async function submitPassport(e) {
      e.preventDefault();
      if (!passportFile.value) {
        alert('Please choose a file first.');
        return;
      }
      const fd = new FormData();
      fd.append('passport', passportFile.value);
      fd.append('candidate_id', form.id);

      const url = `/candidates/profile/upload-passport`; // ensure route exists or change accordingly
      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          body: fd
        });
        if (!res.ok) throw new Error('Upload failed');
        const data = await res.json().catch(() => ({}));
        alert('Passport uploaded');
        // optionally update candidate passport path if returned
        if (data.passport_url) passportPreview.value = data.passport_url;
      } catch (err) {
        console.error(err);
        alert('Passport upload failed');
      }
    }

    // Lists (initialized from props.candidate if present)
    const medicalSchools = ref(props.candidate?.medicalSchools || []);
    const institutions = ref(props.candidate?.institutions || []);
    const postgrads = ref(props.candidate?.postgraduateTrainings || props.candidate?.postgraduates || []);

    // Edit med school state
    const editMedSchoolId = ref(null);
    const editMedSchoolData = reactive({ id: null, name: '', start_date: '', end_date: '' });

    function openEditMedSchoolModal(school) {
      editMedSchoolId.value = school.id;
      editMedSchoolData.id = school.id;
      editMedSchoolData.name = school.name;
      editMedSchoolData.start_date = school.start_date;
      editMedSchoolData.end_date = school.end_date;
      const modalEl = document.getElementById(`editSchoolModal${school.id}`);
      if (modalEl && window.bootstrap) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
      }
    }

    // Helper to format labels
    function formatLabel(field) {
      return field.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase());
    }

    // CRUD handlers for Medical School (AJAX)
    async function saveMedicalSchool(e) {
      e.preventDefault();
      const formEl = e.target;
      const fd = new FormData(formEl);
      fd.append('candidate_id', form.id);

      try {
        const res = await fetch('/candidates/profile/save-medical-school', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Save failed');
        // try to get json || fallback to reload
        const data = await res.json().catch(() => null);
        // optimistic: push to local list if response not full
        if (data && data.id) {
          medicalSchools.value.push(data);
        } else {
          // reload or fetch updated list (simple approach: reload page)
          window.location.reload();
        }
      } catch (err) {
        console.error(err);
        alert('Failed to save medical school');
      }
    }

    async function updateMedicalSchool(e) {
      e.preventDefault();
      const fd = new FormData(e.target);
      try {
        const res = await fetch('/candidates/profile/update-medical-school', {
          method: 'POST', // controller expects POST in your routes
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Update failed');
        window.location.reload();
      } catch (err) {
        console.error(err);
        alert('Failed to update');
      }
    }

    async function deleteMedicalSchool(id) {
      if (!confirm('Are you sure to delete this entry?')) return;
      const fd = new FormData();
      fd.append('id', id);
      try {
        const res = await fetch('/candidates/profile/delete-medical-school', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Delete failed');
        window.location.reload();
      } catch (err) {
        console.error(err);
        alert('Failed to delete');
      }
    }

    // Institutions handlers
    async function saveInstitution(e) {
      e.preventDefault();
      const fd = new FormData(e.target);
      fd.append('candidate_id', form.id);
      try {
        const res = await fetch('/candidates/profile/save-institution', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Save failed');
        window.location.reload();
      } catch (err) {
        console.error(err);
        alert('Failed to save institution');
      }
    }

    async function deleteInstitution(id) {
      if (!confirm('Are you sure to delete this institution?')) return;
      const fd = new FormData();
      fd.append('id', id);
      try {
        const res = await fetch('/candidates/profile/delete-institution', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Delete failed');
        window.location.reload();
      } catch (err) {
        console.error(err);
        alert('Failed to delete institution');
      }
    }

    // Postgrad handlers
    async function savePostgrad(e) {
      e.preventDefault();
      const fd = new FormData(e.target);
      fd.append('candidate_id', form.id);
      try {
        const res = await fetch('/candidates/profile/save-postgraduate-training', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Save failed');
        // server returns JSON success -> reload or optimistic add
        const data = await res.json().catch(() => null);
        if (data && data.status === 'success') {
          window.location.reload();
        } else {
          window.location.reload();
        }
      } catch (err) {
        console.error(err);
        alert('Failed to save postgraduate training');
      }
    }

    async function deletePostgrad(id) {
      if (!confirm('Are you sure to delete this entry?')) return;
      const fd = new FormData();
      fd.append('id', id);
      try {
        const res = await fetch('/candidates/profile/delete-postgrad', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: fd
        });
        if (!res.ok) throw new Error('Delete failed');
        window.location.reload();
      } catch (err) {
        console.error(err);
        alert('Failed to delete postgraduate training');
      }
    }

    // Update candidate details (PUT to /candidates/{id})
    async function updateCandidateDetails(e) {
      e.preventDefault();
      const payload = { ...form };
      const url = `/candidates/${form.id}`;
      try {
        const res = await fetch(url, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(payload)
        });
        if (!res.ok) {
          const text = await res.text().catch(() => '');
          throw new Error('Update failed: ' + text);
        }
        alert('Candidate updated');
        window.location.reload();
      } catch (err) {
        console.error(err);
        alert('Failed to update candidate details');
      }
    }

    // onMounted: optionally fetch fresh lists if props didn't provide them
    onMounted(() => {
      // If lists empty, optionally fetch from API endpoints (implement if needed)
      // Example (uncomment if you create these endpoints):
      // if (!medicalSchools.value.length && form.id) {
      //   fetch(`/candidates/profile/load-medical-schools?id=${form.id}`)
      //     .then(r => r.json()).then(data => medicalSchools.value = data);
      // }
    });

    return {
      // data
      fields,
      form,
      passportPreview,
      medicalSchools,
      institutions,
      postgrads,
      showMedSchoolForm,
      showInstitutionForm,
      showPostgradForm,
      editMedSchoolId,
      editMedSchoolData,

      // helpers & handlers
      onPassportClick,
      onPassportChange,
      submitPassport,
      openEditMedSchoolModal,
      saveMedicalSchool,
      updateMedicalSchool,
      deleteMedicalSchool,
      saveInstitution,
      deleteInstitution,
      savePostgrad,
      deletePostgrad,
      updateCandidateDetails,
      formatLabel
    };
  },

  // --- template as a JS template string ---
  template: `
<div class="container-fluid">
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
            title="Click to select passport image"
            @click.prevent="onPassportClick"
          >
            <img
              :src="passportPreview"
              alt="Passport"
              style="max-width: 100px; max-height: 100px; object-fit: contain; pointer-events: none;"
            />
            <input
              type="file"
              name="passport"
              id="passportInput"
              accept="image/*"
              style="display: none"
              @change="onPassportChange"
            />
          </div>

          <small class="text-muted d-block mt-1">Click image area or drag & drop</small>
          <button type="submit" class="btn btn-sm btn-primary mt-2" id="uploadButton">Upload</button>
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
        <thead>
          <tr>
            <th>School</th>
            <th>Start</th>
            <th>End</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="school in medicalSchools" :key="school.id">
            <td>{{ school.name }}</td>
            <td>{{ school.start_date }}</td>
            <td>{{ school.end_date }}</td>
            <td>
              <button
                class="btn btn-warning btn-sm"
                type="button"
                @click="openEditMedSchoolModal(school)"
                data-bs-toggle="modal"
                :data-bs-target="'#editSchoolModal' + school.id"
              >Edit</button>

              <button class="btn btn-danger btn-sm" type="button" @click="deleteMedicalSchool(school.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- EDIT MODALS -->
      <div v-for="school in medicalSchools" :key="'modal-' + school.id" class="modal fade" :id="'editSchoolModal' + school.id" tabindex="-1" :aria-labelledby="'editSchoolLabel' + school.id" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="updateMedicalSchool">
              <div class="modal-header">
                <h5 class="modal-title" :id="'editSchoolLabel' + school.id">Edit Medical School</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id" :value="school.id" />
                <input type="hidden" name="candidate_id" :value="form.id" />
                <div class="mb-2">
                  <input type="text" name="name" class="form-control" v-model="editMedSchoolData.name" required />
                </div>
                <div class="mb-2">
                  <input type="date" name="start_date" class="form-control" v-model="editMedSchoolData.start_date" required />
                </div>
                <div class="mb-2">
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
      <!-- END EDIT MODALS -->

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
          <input type="text" name="post_training_school" class="form-control" placeholder="Post Training School" required />
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
      <form @submit.prevent="updateCandidateDetails">
        <div class="row">
          <div v-for="field in fields" :key="field" class="col-md-6 mb-2">
            <select v-if="field === 'gender'" name="gender" class="form-control" v-model="form.gender">
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>

            <select v-else-if="field === 'fellowship_type'" name="fellowship_type" class="form-control" v-model="form.fellowship_type">
              <option value="">Select Fellowship Type</option>
              <option value="Part I">Part I</option>
              <option value="Part II">Part II</option>
            </select>

            <input v-else
              :type="field.includes('date') || field === 'dob' ? 'date' : 'text'"
              :name="field"
              class="form-control"
              :placeholder="formatLabel(field)"
              v-model="form[field]"
            />
          </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update Details</button>
      </form>
    </div>
  </div>
</div>
`
};

