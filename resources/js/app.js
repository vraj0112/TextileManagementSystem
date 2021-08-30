require('./bootstrap');
require('../../public/dist/js/adminlte.min.js');
require('../../node_modules/bootstrap4-toggle/js/bootstrap4-toggle.min');

import App from './components/App';
import router from '../router';

window.Vue = require('vue').default;

const app = new Vue({
    el: '#app',
    router,
    components: {
        App
    }
});