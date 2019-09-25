import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        auth: {
            loggedIn: false,
            userId: null
        },
        count: 0
    },
    mutations: {
        increment (state) {
            state.count++
        }
    }
});

export default store;
