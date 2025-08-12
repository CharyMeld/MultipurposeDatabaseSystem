<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  candidateId: { type: Number, required: true }
})

const loading = ref(true)
const error = ref(null)
const candidate = ref({})
const facultyName = ref('')
const subSpeciality = ref('')
const medicalSchools = ref([])
const institutions = ref([])
const postgraduates = ref([])

onMounted(async () => {
  try {
    // Use the correct API endpoint matching your backend route:
    const { data } = await axios.get(`/candidates/${props.candidateId}/json`)

    // Set candidate with default fields and overwrite with actual data
    candidate.value = {
      id: '',
      registration_number: '',
      cert_number: '',
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
      passport: '',
      created_at: '',
      updated_at: '',
      ...data.candidate // overwrite defaults with backend data
    }

    // Fix passport URL if needed
    if (candidate.value.passport && !candidate.value.passport.startsWith('http')) {
      candidate.value.passport = `/storage/passports/${candidate.value.passport}`
    }

    // Use exact camelCase keys from backend response
    facultyName.value = data.facultyName || ''
    subSpeciality.value = data.subSpeciality || ''
    medicalSchools.value = data.medicalSchools || []
    institutions.value = data.institutions || []
    postgraduates.value = data.postgraduates || []

  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <div v-if="loading">Loading Candidate Profile...</div>
    <div v-else-if="error" class="text-danger">{{ error }}</div>
    <div v-else>
      <!-- PASSPORT -->
      <div class="candidate-image-container mb-4">
        <div
          class="border rounded-2 d-inline-block"
          style="width: 150px; height: 150px; overflow: hidden; background-color: #f8f9fa;"
        >
          <img
            :src="candidate.passport || '/assets/images/placeholder.png'"
            alt="Passport"
            style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;"
          />
        </div>
      </div>

      <!-- FULL BIODATA TABLE -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Candidate Biodata</div>
        <div class="card-body">
          <table class="table table-bordered table-sm">
            <tbody>
              <tr v-for="(value, key) in candidate" :key="key">
                <th>{{ key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) }}</th>
                <td>{{ value || '—' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- MEDICAL SCHOOL -->
      <div v-if="medicalSchools.length" class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">Medical School Attended</div>
        <div class="card-body">
          <ul class="list-group">
            <li v-for="(school, index) in medicalSchools" :key="index" class="list-group-item">
              {{ school.name }}
            </li>
          </ul>
        </div>
      </div>

      <!-- INSTITUTIONS ATTENDED -->
      <div v-if="institutions.length" class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">Institution(s) Attended</div>
        <div class="card-body">
          <ul class="list-group">
            <li v-for="(institution, index) in institutions" :key="index" class="list-group-item">
              {{ institution.name }}
            </li>
          </ul>
        </div>
      </div>

      <!-- POSTGRADUATE TRAINING -->
      <div v-if="postgraduates.length" class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">Postgraduate Training</div>
        <div class="card-body">
          <ul class="list-group">
            <li v-for="(pg, index) in postgraduates" :key="index" class="list-group-item">
              {{ pg.field || pg.post_training_school || 'N/A' }}
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</template>

