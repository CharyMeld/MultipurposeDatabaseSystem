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

const allowedFields = [
  'id',
  'registration_number',
  'cert_number',
  'surname',
  'other_name',
  'maiden_name',
  'change_of_name',
  'email',
  'phone',
  'address',
  'postal_address',
  'dob',
  'gender',
  'nationality',
  'fellowship_type',
  'country',
  'faculty_id',
  'sub_speciality',
  'full_registration_date',
  'entry_mode',
  'nysc_discharge_or_exemption',
  'prefered_exam_center',
  'accredited_training_program',
  'post_registration_appointment',
]

onMounted(async () => {
  try {
    const { data } = await axios.get(`/candidates/${props.candidateId}/json`)

    // Keep only allowed fields
    candidate.value = {}
    allowedFields.forEach(field => {
      candidate.value[field] = data.candidate[field] || ''
    })

    // Fix passport URL
    if (candidate.value.passport && !candidate.value.passport.startsWith('http')) {
      candidate.value.passport = `/storage/passports/${candidate.value.passport}`
    }

    // Flatten nested objects/arrays
    facultyName.value = data.facultyName || (data.candidate.faculty?.name || '')
    subSpeciality.value = data.subSpeciality || ''
    medicalSchools.value = (data.medicalSchools || []).map(s => s.name || '—')
    institutions.value = (data.institutions || []).map(i => i.name || '—')

    // Fix Postgraduates mapping to ensure 'name' is present
    postgraduates.value = (data.postgraduates || []).map(p => ({
      id: p.id,
      name: p.name || p.post_training_school || 'N/A'
    }))

  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
})
</script>


<template>
  <div>
    <div v-if="loading" class="text-center py-3">Loading Candidate Profile...</div>
    <div v-else-if="error" class="text-danger">{{ error }}</div>
    <div v-else>
      <!-- PASSPORT -->
      <div class="candidate-image-container mb-4 text-center">
        <div
          class="border rounded-2 d-inline-block"
          style="width: 150px; height: 150px; overflow: hidden; background-color: #1f2937;"
        >
          <img
            :src="candidate.passport || '/assets/images/placeholder.png'"
            alt="Passport"
            style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;"
          />
        </div>
      </div>

      <!-- FULL BIODATA - 3 COLUMNS -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Candidate Biodata</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4 mb-2">
              <strong>Faculty:</strong>
              <div>{{ facultyName || '—' }}</div>
            </div>
            <div
              v-for="(value, key) in candidate"
              :key="key"
              class="col-md-4 mb-2"
            >
              <strong>{{ key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) }}:</strong>
              <div>{{ value || '—' }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- MEDICAL SCHOOL -->
      <div v-if="medicalSchools.length" class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">Medical School Attended</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li v-for="(name, index) in medicalSchools" :key="index" class="list-group-item">
              {{ name }}
            </li>
          </ul>
        </div>
      </div>

      <!-- INSTITUTIONS ATTENDED -->
      <div v-if="institutions.length" class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">Institution(s) Attended</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li v-for="(name, index) in institutions" :key="index" class="list-group-item">
              {{ name }}
            </li>
          </ul>
        </div>
      </div>




        <!-- POSTGRADUATE TRAINING -->
        <div v-if="postgraduates.length" class="card shadow-sm mb-4 mt-4">
          <div class="card-header bg-warning text-dark">Postgraduate Training</div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li v-for="pg in postgraduates" :key="pg.id" class="list-group-item">
                {{ pg.name }}
              </li>
            </ul>
          </div>
        </div>
    </div>
  </div>
</template>

