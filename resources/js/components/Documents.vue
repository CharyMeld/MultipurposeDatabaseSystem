<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

// Props
const props = defineProps({
  candidateId: { type: Number, required: true }
})

// Form fields
const selectedApplicationId = ref(null)
const title = ref('')
const description = ref('')
const file = ref(null)
const status = ref(true)

// Upload state
const uploading = ref(false)
const uploadProgress = ref(0)
const error = ref(null)
const successMessage = ref(null)

// Data lists
const applications = ref([])
const documents = ref([])

// Fetch candidate applications and documents on mount or when candidateId changes
async function fetchApplicationsAndDocuments() {
  error.value = null
  try {
    const { data } = await axios.get(`/api/candidates/${props.candidateId}/applications-documents`)
    applications.value = data.applications || []
    documents.value = data.documents || []
    if(applications.value.length > 0){
      selectedApplicationId.value = applications.value[0].id // default selection
    }
  } catch (err) {
    error.value = 'Failed to load applications or documents.'
  }
}

onMounted(fetchApplicationsAndDocuments)
watch(() => props.candidateId, fetchApplicationsAndDocuments)

// File input change handler
function onFileChange(event) {
  const selectedFile = event.target.files[0]
  if (selectedFile) {
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']
    if (!allowedTypes.includes(selectedFile.type)) {
      error.value = 'Invalid file type. Allowed: PDF, JPEG, PNG, JPG.'
      file.value = null
      return
    }
    if (selectedFile.size > 200000000) {
      error.value = 'File size exceeds 200MB limit.'
      file.value = null
      return
    }
    error.value = null
    file.value = selectedFile
  }
}

// Submit upload
async function submit() {
  if (!selectedApplicationId.value) {
    error.value = 'Please select an application.'
    return
  }
  if (!title.value || !file.value) {
    error.value = 'Please provide title and select a file.'
    return
  }
  error.value = null
  successMessage.value = null
  uploading.value = true
  uploadProgress.value = 0

  const formData = new FormData()
  formData.append('candidate_id', props.candidateId)
  formData.append('application_id', selectedApplicationId.value)
  formData.append('title', title.value)
  formData.append('description', description.value)
  formData.append('status', status.value ? 1 : 0)
  formData.append('location', file.value)

  try {
    const response = await axios.post('/documents/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
      onUploadProgress: (progressEvent) => {
        if (progressEvent.lengthComputable) {
          uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        }
      }
    })
    successMessage.value = 'Document uploaded successfully!'
    // Reset form
    title.value = ''
    description.value = ''
    file.value = null
    // Reload documents list
    await fetchApplicationsAndDocuments()
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Upload failed.'
  } finally {
    uploading.value = false
  }
}

// Document actions: download, view, edit, delete
function downloadDocument(doc) {
  window.open(`/storage/${doc.location}`, '_blank')
}

function viewDocument(doc) {
  // Open in new tab, you might want to customize for PDF/image viewer
  window.open(`/storage/${doc.location}`, '_blank')
}

async function deleteDocument(doc) {
  if (!confirm(`Delete document "${doc.title}"?`)) return
  try {
    await axios.delete(`/documents/${doc.id}`)
    successMessage.value = `Deleted "${doc.title}"`
    await fetchApplicationsAndDocuments()
  } catch {
    error.value = 'Failed to delete document.'
  }
}

// Group documents by application type for display
import { computed } from 'vue'
const documentsByAppType = computed(() => {
  const groups = {
    Primary: [],
    Membership: [],
    Fellowship: []
  }
  documents.value.forEach(doc => {
    const app = applications.value.find(a => a.id === doc.application_id)
    if (app) {
      const type = app.type || 'Primary' // fallback type if not set
      if (!groups[type]) groups[type] = []
      groups[type].push(doc)
    }
  })
  return groups
})
</script>

<template>
  <div class="document-upload-form">
    <h3>Upload Document</h3>

    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

    <form @submit.prevent="submit" enctype="multipart/form-data" novalidate>
      <div class="mb-3">
        <label for="application" class="form-label">Application *</label>
        <select v-model="selectedApplicationId" id="application" class="form-select" required>
          <option v-for="app in applications" :key="app.id" :value="app.id">
            {{ app.type }} - {{ app.name || app.title || 'Application #' + app.id }}
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="title" class="form-label">Document Title *</label>
        <input v-model="title" id="title" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Document Description</label>
        <textarea v-model="description" id="description" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label for="file" class="form-label">Upload Document (PDF, JPEG, PNG, JPG; max 200MB) *</label>
        <input id="file" type="file" class="form-control" @change="onFileChange" required />
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" id="status" class="form-check-input" v-model="status" />
        <label for="status" class="form-check-label">Status (Active)</label>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="uploading">Upload</button>
    </form>

    <div v-if="uploading" class="mt-3">
      <div class="progress">
        <div
          class="progress-bar progress-bar-striped progress-bar-animated"
          role="progressbar"
          :style="{ width: uploadProgress + '%' }"
          :aria-valuenow="uploadProgress"
          aria-valuemin="0"
          aria-valuemax="100"
        >
          {{ uploadProgress }}%
        </div>
      </div>
    </div>

    <!-- Display uploaded documents grouped by application type -->
    <div v-for="(docs, type) in documentsByAppType" :key="type" class="mt-5">
      <h4>{{ type }} Documents</h4>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Uploaded At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="docs.length === 0">
            <td colspan="4" class="text-center text-muted">No documents for {{ type }}</td>
          </tr>
          <tr v-for="doc in docs" :key="doc.id">
            <td>{{ doc.title }}</td>
            <td>{{ doc.description || '—' }}</td>
            <td>{{ new Date(doc.time_created).toLocaleString() }}</td>
            <td>
              <button class="btn btn-sm btn-info me-1" @click="viewDocument(doc)">View</button>
              <button class="btn btn-sm btn-secondary me-1" @click="downloadDocument(doc)">Download</button>
              <!-- Edit can open a modal or navigate to an edit form - placeholder -->
              <button class="btn btn-sm btn-warning me-1" @click.prevent="$emit('edit-document', doc)">Edit</button>
              <button class="btn btn-sm btn-danger" @click="deleteDocument(doc)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.document-upload-form {
  max-width: 700px;
  margin: auto;
}
</style>

