require('./bootstrap');

window.Vue = require('vue');
import Modal from './plugins/modal/ModalPlugin'


// generate the global flash method to display flash messages
window.Event = new Vue();

window.flash = function (message, level = 'success') {
    window.Event.$emit('flash', { message, level });
};

Vue.use(Modal);

Vue.component('flash-component', require('./components/FlashComponent.vue').default);
Vue.component('app-component', require('./components/AppComponent.vue').default);

const app = new Vue({
    el: '#app',
});
