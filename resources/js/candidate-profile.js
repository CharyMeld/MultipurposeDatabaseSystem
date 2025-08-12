import { createApp } from 'vue';
import ProfileTabs from './components/ProfileTabs.vue'; // the main tab switcher

const app = createApp(ProfileTabs, {
    candidate: window.App.candidate
});

app.mount('#candidate-profile');

