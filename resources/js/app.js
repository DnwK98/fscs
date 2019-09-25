import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'

Vue.use(VueRouter);
Vue.use(Vuex);

import App from './components/AppComponent'

import router from './router'
import store from './store'

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
});
