/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/* Components Javascript Import*/
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

//socket server
import io from "socket.io-client";

//const socket = io.connect("http://localhost:30001");
const socket = io.connect("https://chatserver.mytutor-jpn.info:30001");



 


    
//css
//import 'bootstrap/dist/css/bootstrap.css'
//import 'bootstrap-vue/dist/bootstrap-vue.css'

// Optionally install the BootstrapVue icon components plugin
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('file-upload', VueUploadComponent);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter('formatSize', function(size) {
    if (size > 1024 * 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024 / 1024).toFixed(2) + ' TB'
    } else if (size > 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB'
    } else if (size > 1024 * 1024) {
        return (size / 1024 / 1024).toFixed(2) + ' MB'
    } else if (size > 1024) {
        return (size / 1024).toFixed(2) + ' KB'
    }

    return size + ' B'
})

const app = new Vue({
    el: '#app',
    components: {
        //draggable: window['vuedraggable'],
        //VueUploadComponent: window['vue-upload-component']
    },
});


/* non-render blocking css files */
var deferCSS = [];
deferCSS.push(
    //"//fonts.googleapis.com/css?family=Nunito&display=swap",
    //window.location.protocol + "//" + window.location.host + "/css/app.css"
);
