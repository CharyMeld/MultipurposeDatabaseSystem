<template>
  <div>
    <ul class="nav nav-tabs">
      <li class="nav-item" v-for="tab in tabs" :key="tab">
        <button
          class="nav-link"
          :class="{ active: activeTab === tab }"
          @click="activeTab = tab"
        >
          {{ tab }}
        </button>
      </li>
    </ul>

    <div class="mt-3">
      <Overview
        v-if="activeTab === 'Overview'"
        :candidate-id="candidate?.id"
        :candidate="candidate"
      />
      <Biodata
        v-if="activeTab === 'Biodata'"
        :candidate="candidate"
      />
      <ApplicationList
        v-if="activeTab === 'Applications'"
        :candidate="candidate"
      />
      <Documents
        v-if="activeTab === 'Documents'"
        :candidate="candidate"
      />
      <Results
        v-if="activeTab === 'Results'"
        :candidate="candidate"
      />
    </div>
  </div>
</template>

<script>
import Overview from './Overview.vue';
import Biodata from './Biodata.vue';
import ApplicationList from './ApplicationList.vue';
import Documents from './Documents.vue';
import Results from './Results.vue';

export default {
  props: ['candidate'],
  components: { Overview, Biodata, ApplicationList, Documents, Results },
  data() {
    return {
      tabs: ['Overview', 'Biodata', 'Applications', 'Documents', 'Results'],
      activeTab: 'Overview'
    };
  }
};
</script>

