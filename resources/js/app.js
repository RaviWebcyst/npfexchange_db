/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';
import { createApp } from 'vue';


//require('./bootstrap');

//window.Vue = require('vue');


const app = createApp({});


// import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';
import router from './router';

import Clipboard from 'v-clipboard';
import VueCountdownTimer from 'vuejs-countdown-timer';




import Toaster from 'v-toaster';

// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-toaster/dist/v-toaster.css'

// optional set default imeout, the default is 10000 (10 seconds).
app.use(Toaster, {timeout: 5000})

app.use(Clipboard);
app.use(VueCountdownTimer);

import VueQRCodeComponent from 'vue-qrcode-component'
app.component('qr-code', VueQRCodeComponent)


import moment from 'moment-timezone';
moment.tz.setDefault('Asia/Kolkata');

// Vue.use(BootstrapVue);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

app.component('example-component', require('./components/ExampleComponent.vue'));
app.component('mainapp', require('./components/mainapp.vue'));
app.component('sidebar', require('./components/pages_old/sidebar.vue'));
app.component('Header', require('./components/pages/header.vue'));
// Vue.component('Spinner', require('./components/pages/Spinner.vue'));
app.component('Footer', require('./components/pages/footer.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

app.use(router);

// Mount the app
app.mount('#app');

