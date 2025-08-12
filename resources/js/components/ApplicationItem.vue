<template>
  <div style="border: 1px solid #aaa; margin-bottom: 8px; padding: 8px;">
    <div
      style="cursor: pointer; font-weight: bold;"
      @click="showDetails = !showDetails"
    >
      {{ application.applicationType }} - Exam#: {{ application.examNumber }}
    </div>
    <div>
      <button @click="$emit('edit', application)">Edit</button>
      <button @click="$emit('delete', application)">Delete</button>
    </div>
    <div v-if="showDetails" style="margin-top: 8px; background-color: #f9f9f9; padding: 8px;">
      <div><b>Mode of Entry:</b> {{ application.modeOfEntry }}</div>
      <div><b>Exam Date:</b> {{ application.examDate }}</div>
      <div><b>Exam Center:</b> {{ application.examCenter }}</div>
      <div><b>Date Applied:</b> {{ application.dateApplied }}</div>
      <div><b>Date Approved:</b> {{ application.dateApproved }}</div>
      <div><b>Passed:</b> {{ application.passed }}</div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  application: Object,
})

const showDetails = ref(false)
</script>

