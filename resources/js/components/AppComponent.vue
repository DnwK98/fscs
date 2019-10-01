<template>
    <div class="mian">
        <div class="menu">
            <side-menu />
        </div>
        <div class="content" v-if="auth.loggedIn">
            <div class="router">
                <router-view />
            </div>
        </div>
        <div v-else>
            <authentication />
        </div>
        <div style="clear: both"></div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import store from "../store";
    import { mapState } from 'vuex'
    import Authentication from "./AuthenticationComponent";
    import SideMenu from "./SideMenuComponent";


    export default Vue.component('app', {
        mounted() {
            console.log('App mounted.');
            store.commit("increment");
            console.log(store.state);
        },
        components: {
            Authentication,
            SideMenu
        },
        computed: mapState([
            "auth"
        ])
    })
</script>

<style scoped lang="scss">

    @import "../../sass/_variables.scss";

    .main {
        width: 100%;
        height: 100%;
    }

    .menu {
        float: left;
    }

    .content {
        overflow:hidden;
    }


</style>
