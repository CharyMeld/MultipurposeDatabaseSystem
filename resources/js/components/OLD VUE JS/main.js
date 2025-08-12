import { createApp } from 'vue';

import Overview from './components/Overview.vue';
import Biodata from './components/Biodata.vue';
import ApplicationList from './components/ApplicationList.vue';
import Documents from './components/Documents.vue';
import Results from './components/Results.vue';
import AppForm from './components/AppForm.vue';

const App = {
  components: {
    Overview,
    Biodata,
    ApplicationList,
    Documents,
    Results,
    AppForm,
  },
  data() {
    return {
      activeTab: 'Overview',
      candidate: window.candidateData || {},
      faculty_name: window.facultyName || '',
      sub_speciality: window.subSpeciality || '',
      doc_fields: window.docFields || [],
      full_name: window.fullName || '',
      medicalSchools: window.medicalSchools || [],
      institutions: window.institutions || [],
      postgraduates: window.postgraduates || [],
      formSubmitted: false,
    };
  },
  methods: {
    switchTab(tab) {
      const validTabs = ['Overview', 'Biodata', 'Application', 'Documents', 'Results'];
      this.activeTab = validTabs.includes(tab) ? tab : 'Overview';
    },
    handleFormSubmitted() {
      this.formSubmitted = true;
      setTimeout(() => (this.formSubmitted = false), 3000);
    },
    handleUpdate(...args) {
      console.log('Data updated:', args);
    },
  },
  template: `
    <div class="container mt-4">
      <ul class="nav nav-tabs">
        <li class="nav-item" v-for="tab in ['Overview', 'Biodata', 'Application', 'Documents', 'Results']" :key="tab">
          <a class="nav-link" :class="{ active: activeTab === tab }" href="#" @click.prevent="switchTab(tab)">
            {{ tab }}
          </a>
        </li>
      </ul>

      <div class="tab-content mt-3">
        <div v-if="activeTab === 'Overview'">
          <Overview
            :candidate="candidate"
            :faculty_name="faculty_name"
            :sub_speciality="sub_speciality"
            :medicalSchools="medicalSchools"
            :institutions="institutions"
            :postgraduates="postgraduates"
          />
        </div>

        <div v-if="activeTab === 'Biodata'">
          <Biodata
            v-if="candidate && candidate.biodata"
            :faculty_name="faculty_name"
            :sub_speciality="sub_speciality"
            :candidate="candidate"
            @init-update="handleUpdate"
            @form-submitted="handleFormSubmitted"
          />
        </div>

        <div v-if="activeTab === 'Application'">
          <ApplicationList />
        </div>

        <div v-if="activeTab === 'Documents'">
          <Documents v-if="candidate && candidate.id" :candidate="candidate" :doc_fields="doc_fields" />
        </div>

        <div v-if="activeTab === 'Results'">
          <Results v-if="candidate && candidate.results" :candidate="candidate" />
        </div>

        <div v-if="formSubmitted" class="alert alert-success mt-3">
          Form submitted successfully!
        </div>
      </div>
    </div>
  `,
};

createApp(App).mount('#app');

