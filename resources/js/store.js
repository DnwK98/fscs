import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

let store = new Vuex.Store({
    state: {
        auth: {
            loggedIn: false,
            loginWindow: false,
            userId: null,
            userName: null,
            userToken: null
        },
        count: 0
    },
    mutations: {
        increment (state) {
            state.count++
        },
        tokenExpired(state) {
            state.auth.loggedIn = false;
            state.auth.loginWindow = true;
        }
    }
});

export default store;
