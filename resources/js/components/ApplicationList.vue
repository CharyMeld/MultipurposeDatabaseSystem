<template>
  <div>
    <!-- Switch key between edit/new to reset form -->
    <ApplicationForm
      :key="editingApp ? 'edit' : 'new'"
      :modelValue="editingApp"
      @save="addOrUpdateApplication"
      @cancel="cancelEdit"
    />

    <!-- Only show sections that have at least one application -->
    <div
      v-for="type in APPLICATION_TYPES"
      :key="type"
      v-if="groupedApps[type] && groupedApps[type].length > 0"
      style="margin-bottom: 30px;"
    >
      <h2>{{ type }} Applications</h2>

      <!-- Use app.id instead of index -->
      <ApplicationItem
        v-for="app in groupedApps[type]"
        :key="app.id"
        :application="app"
        @edit="editApplication"
        @delete="deleteApplication"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import ApplicationForm from './ApplicationForm.vue'
import ApplicationItem from './ApplicationItem.vue'

const APPLICATION_TYPES = ['Primary', 'Membership', 'Fellowship']
const applications = ref([])
const editingApp = ref(null)

const groupedApps = computed(() => {
  const groups = {}
  APPLICATION_TYPES.forEach(type => {
    groups[type] = applications.value.filter(a => a.applicationType === type)
  })
  return groups
})

// Fetch apps from backend when page loads
onMounted(async () => {
  try {
    const res = await axios.get('/api/applications') // adjust to your route
    applications.value = res.data
  } catch (err) {
    console.error('Error fetching applications', err)
  }
})

async function addOrUpdateApplication(app) {
  try {
    if (editingApp.value) {
      const res = await axios.put(`/api/applications/${editingApp.value.id}`, app)
      const index = applications.value.findIndex(a => a.id === editingApp.value.id)
      if (index !== -1) {
        applications.value[index] = res.data
      }
      editingApp.value = null
    } else {
      const res = await axios.post('/api/applications', app)
      applications.value.push(res.data)
    }
  } catch (err) {
    console.error('Error saving application', err)
  }
}

function editApplication(app) {
  editingApp.value = { ...app }
}

function cancelEdit() {
  editingApp.value = null
}

async function deleteApplication(app) {
  if (confirm('Are you sure you want to delete this application?')) {
    try {
      await axios.delete(`/api/applications/${app.id}`)
      applications.value = applications.value.filter(a => a.id !== app.id)
      if (editingApp.value && editingApp.value.id === app.id) {
        editingApp.value = null
      }
    } catch (err) {
      console.error('Error deleting application', err)
    }
  }
}
</script>

