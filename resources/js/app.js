require('./bootstrap');
require('../../public/dist/js/adminlte.min.js');
require('../../node_modules/bootstrap4-toggle/js/bootstrap4-toggle.min.js');
require('../../node_modules/sweetalert2/dist/sweetalert2.all.min.js');
require('../../node_modules/toastr/build/toastr.min.js');
// require('../../node_modules/select2/dist/js/select2.full.min.js');


import App from './components/App';
import router from '../router';

window.Vue = require('vue').default;

Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app',
    router,
    components: {
        App
    }
});