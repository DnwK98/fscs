<template>
    <div class="mian">
        <div class="menu">

        </div>
        <div v-if="auth.loggedIn">
            <div class="router">
                <router-view />
            </div>
        </div>
        <div v-else>
            <authentication />
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import store from "../store";
    import { mapState } from 'vuex'
    import Authentication from "./AuthenticationComponent";


    export default Vue.component('app', {
        mounted() {
            console.log('App mounted.');
            store.commit("increment");
            console.log(store.state);
        },
        computed: mapState([
            "auth"
        ])
    })
</script>

<style>

</style>
