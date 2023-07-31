import axios from 'axios';
import {createApp} from 'vue'
import router from './route'
import App from './App.vue'
import VSelect from 'vue-select';
import "vue-select/dist/vue-select.css";

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp(App)

app.use(router)
app.component("v-select", VSelect)
app.mount("#app")
