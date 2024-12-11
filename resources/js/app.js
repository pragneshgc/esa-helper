import axios from "axios";
import { createApp } from "vue";

import router from './router';
import EsaHelper from "./EsaHelper.vue";
import 'bootstrap';

let token = document.head.querySelector("meta[name='csrf-token']");

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

const app = createApp(EsaHelper);

//plugins
app.use(router);

app.mount('#esa-helper');