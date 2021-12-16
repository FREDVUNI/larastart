require("./bootstrap");
window.Vue = require('vue').default;

// form manipulation
import Form from "./form";
window.Form = Form;

import { createApp } from "vue";

//sweet alerts
import VueSweetalert2 from "vue-sweetalert2";
import 'vue-sweetalert2/node_modules/sweetalert2/dist/sweetalert2.min.css';

// Root App
import App from "./components/App.vue"; 
// Vue Router
import router from "./router";

const app = createApp(App);
app.use(router).use(VueSweetalert2).mount("#app");

