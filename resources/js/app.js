/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import router from './router';
import VueFlashMessage from 'vue-flash-message';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'


window.Vue = require('vue');



Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueFlashMessage);



Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('app-component', require('./components/AppComponent.vue').default);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

const app = new Vue({
    el: '#app',
    router
});

