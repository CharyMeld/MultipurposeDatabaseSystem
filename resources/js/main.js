import { createApp, ref } from 'vue';
import Overview from './components/Overview.vue';
import Biodata from './components/Biodata.vue';
import ApplicationList from './components/ApplicationList.vue';
import Documents from './components/Documents.vue';
import Results from './components/Results.vue';
import './bootstrap';

const el = document.getElementById('app');

// Helper to parse safely
const parseData = (key) => {
  try {
    return JSON.parse(el.dataset[key] || 'null');
  } catch {
    return null;
  }
};

const App = {
  setup() {
    const activeTab = ref('Overview');

    const candidate = ref(parseData('candidate') || {});
    const faculty_name = ref(parseData('facultyName') || '');
    const sub_speciality = ref(parseData('subSpeciality') || '');
    const doc_fields = ref(parseData('docFields') || []);
    const full_name = ref(parseData('fullName') || '');

    const medicalSchools = ref(parseData('medicalSchools') || []);
    const institutions = ref(parseData('institutions') || []);
    const postgraduates = ref(parseData('postgraduates') || []);

    const applications = ref(parseData('applications') || []);
    const applicationTypes = ref(parseData('applicationTypes') || []);
    const results = ref(parseData('results') || []);

    const formSubmitted = ref(false);

    const switchTab = (tab) => {
      const validTabs = ['Overview', 'Biodata', 'Application', 'Documents', 'Results'];
      activeTab.value = validTabs.includes(tab) ? tab : 'Overview';
    };

    const handleFormSubmitted = () => {
      formSubmitted.value = true;
      setTimeout(() => (formSubmitted.value = false), 3000);
    };

    const handleUpdate = (...args) => {
      console.log('Data updated:', args);
    };

    return {
      activeTab,
      candidate,
      faculty_name,
      sub_speciality,
      doc_fields,
      full_name,
      medicalSchools,
      institutions,
      postgraduates,
      applications,
      applicationTypes,
      results,
      formSubmitted,
      switchTab,
      handleFormSubmitted,
      handleUpdate,
    };
  },
  components: {
    Overview,
    Biodata,
    ApplicationList,
    Documents,
    Results,
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
          <div v-else>
            <p>No biodata available.</p>
          </div>
        </div>

        <div v-if="activeTab === 'Application'">
          <ApplicationList
            :applications="applications"
            :applicationTypes="applicationTypes"
          />
        </div>

        <div v-if="activeTab === 'Documents'">
          <Documents
            v-if="candidate && candidate.id"
            :candidate="candidate"
            :doc_fields="doc_fields"
          />
          <div v-else>
            <p>No documents available.</p>
          </div>
        </div>

        <div v-if="activeTab === 'Results'">
          <Results
            v-if="candidate && candidate.results"
            :candidate="candidate"
            :results="results"
          />
          <div v-else>
            <p>No results available.</p>
          </div>
        </div>

        <div v-if="formSubmitted" class="alert alert-success mt-3">
          Form submitted successfully!
        </div>
      </div>
    </div>
  `,
};

createApp(App).mount(el);

