import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import axios from './axios';
import App from './App.vue';

import '../css/app.css';

const app = createApp(App);
app.use(router);
app.config.globalProperties.$axios = axios;

// Make axios globally available
window.axios = axios;

app.mount('#app');