<template>
  <div>
    <!-- Toggle Button -->
    <button v-if="!showForm" class="btn-primary" @click="showForm = true">
      New Application Entry
    </button>

    <!-- Form -->
    <form v-if="showForm" @submit.prevent="submit" class="application-form">
      <h3>{{ isEditMode ? 'Edit Application' : 'New Application Entry' }}</h3>

      <div class="form-grid">
        <label>
          Application Type:
          <select v-model="form.applicationType" required>
            <option value="">--Select--</option>
            <option v-for="type in APPLICATION_TYPES" :key="type" :value="type">{{ type }}</option>
          </select>
        </label>

        <label>
          Mode of Entry:
          <select v-model="form.modeOfEntry" required>
            <option value="">--Select--</option>
            <option v-for="mode in MODE_OF_ENTRY" :key="mode" :value="mode">{{ mode }}</option>
          </select>
        </label>

        <label>
          Exam Number:
          <input type="text" v-model="form.examNumber" required />
        </label>

        <label>
          Exam Date:
          <select v-model="form.examDate" required>
            <option value="">--Select--</option>
            <option v-for="date in EXAM_DATES" :key="date" :value="date">{{ date }}</option>
          </select>
        </label>

        <label>
          Exam Center:
          <select v-model="form.examCenter" required>
            <option value="">--Select--</option>
            <option v-for="center in EXAM_CENTERS" :key="center" :value="center">{{ center }}</option>
          </select>
        </label>

        <label>
          Date Applied:
          <input type="date" v-model="form.dateApplied" required />
        </label>

        <label>
          Date Approved:
          <input type="date" v-model="form.dateApproved" required />
        </label>

        <label>
          Passed:
          <select v-model="form.passed" required>
            <option value="">--Select--</option>
            <option v-for="opt in PASSED_OPTIONS" :key="opt" :value="opt">{{ opt }}</option>
          </select>
        </label>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">
          {{ isEditMode ? 'Update Application' : 'Add Application' }}
        </button>
        <button type="button" class="btn-secondary" @click="cancelForm">
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, watch, computed, ref } from 'vue'

const props = defineProps({
  modelValue: Object,
})

const emit = defineEmits(['save', 'cancel'])

const showForm = ref(false)

const APPLICATION_TYPES = ['Primary', 'Membership', 'Fellowship']
const MODE_OF_ENTRY = [
  'Examination',
  'Fellowship by Election',
  'Fellowship by Progression',
  "Fellowship by Grandfather's Clause",
  'Honorary Fellowship',
  'International Fellowship',
]
const EXAM_DATES = ['A1982', 'A1983', 'A1984', 'A2025', 'B1982', 'B2025']
const EXAM_CENTERS = [
  'Ibadan', 'Accra', 'Monrovia', 'Abuja', 'Zaria', 'Enugu', 'Kaduna', 'Freetown',
]
const PASSED_OPTIONS = ['Yes', 'No', 'Primary Exemption']

const emptyForm = {
  applicationType: '',
  modeOfEntry: '',
  examNumber: '',
  examDate: '',
  examCenter: '',
  dateApplied: '',
  dateApproved: '',
  passed: '',
}

const form = reactive({ ...emptyForm })

watch(() => props.modelValue, (newVal) => {
  if (newVal) {
    Object.assign(form, newVal)
    showForm.value = true // show form if editing
  } else {
    Object.assign(form, emptyForm)
  }
}, { immediate: true })

const isEditMode = computed(() => !!props.modelValue)

function submit() {
  if (!form.applicationType) {
    alert('Please select Application Type')
    return
  }
  emit('save', { ...form })
  Object.assign(form, emptyForm)
  showForm.value = false // hide after submit
}

function cancelForm() {
  emit('cancel')
  Object.assign(form, emptyForm)
  showForm.value = false // hide on cancel
}
</script>

<style scoped>
.application-form {
  border: 1px solid #ccc;
  padding: 16px;
  margin-bottom: 20px;
  background: #fff;
  border-radius: 6px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr; /* two columns */
  gap: 16px;
}

label {
  display: flex;
  flex-direction: column;
  font-weight: bold;
  font-size: 0.95rem;
}

select,
input {
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-top: 4px;
}

.form-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}

.btn-primary {
  background: #27a9e3;
  color: white;
  padding: 8px 14px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary:hover {
  background: #1d91c2;
}

.btn-secondary {
  background: #ccc;
  color: #000;
  padding: 8px 14px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-secondary:hover {
  background: #aaa;
}
</style>

