<template>
    <transition name="fade">
        <div class="auth-main">
            <div class="application-name center-windows">
                FSCS
            </div>
            <div class="application-description center-windows">
                Counter Strike Server Automation
            </div>
            <div style="position: relative" class="center-windows">
                <transition name="fade">
                    <div v-if="auth.loginWindow" class="login-window center-windows">
                        <form @submit="login">
                            <label>Login</label><br/>
                            <input type="text" name="login" :class="{'login-error': auth.hadError}" v-model="username"
                                   autocomplete="off"> <br/>

                            <label>Password</label><br/>
                            <input type="password" name="password" :class="{'login-error': auth.hadError}"
                                   v-model="password" autocomplete="off">

                            <div class="login-error-msg" v-if="auth.hadError">Invalid credentials. Please try again.
                            </div>
                            <input value="Login" type="submit" class="login-submit" @click="login" />
                        </form>
                    </div>
                </transition>
                <div v-if="!auth.loginWindow" class="loader-window center-windows">
                    <rotate-square
                        v-bind:background="'rgba(0,0,0,0.2)'"
                        v-bind:size="'130px'"
                    />
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import Vue from 'vue'
    import {apiGet, apiPost} from './ApiComponent'
    import {mapState} from 'vuex'
    import store from "../store";
    import RotateSquare from 'vue-loading-spinner/src/components/RotateSquare'


    export default Vue.component('authentication', {
        data: function () {
            return {
                username: "",
                password: ""
            }
        },
        components: {
            RotateSquare
        },
        mounted() {
            setTimeout(function () {
                store.state.auth.userToken = getCookie("token");
                apiGet({url: "api/me"}).then(function (response) {
                    store.state.auth.loggedIn = true;
                    store.state.auth.hadError = false;
                    store.state.auth.userName = response.data.name;
                    store.state.auth.userId = response.data.id;
                }).fail(function () {
                    store.state.auth.loginWindow = true;
                })
            }, 1000);
        }, computed: mapState([
            "auth"
        ]),
        methods: {
            login(event) {
                event.preventDefault();
                store.state.auth.loginWindow = false;
                $.ajax({
                    type: "POST",
                    url: "api/token",
                    data: {
                        username: this.username,
                        password: this.password
                    }
                })
                    .then(function (response) {
                        store.state.auth.userToken = response.data.accessToken;
                        document.cookie = "token=" + response.data.accessToken;
                        apiGet({url: "api/me"}).then(function (response) {
                            store.state.auth.loggedIn = true;
                            store.state.auth.userName = response.data.name;
                            store.state.auth.userId = response.data.id;
                        })
                    })
                    .fail(function (response) {
                        setTimeout(function () {
                            store.state.auth.loginWindow = true;
                            store.state.auth.hadError = true;
                        }, 500)
                    })
            }
        }
    })

    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    }

</script>

<style scoped lang="scss">
    @import "../../sass/_variables.scss";

    .auth-main {
        font-family: 'Nunito', sans-serif;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background-color: $mainColor;
    }

    .center-windows {
        margin-left: auto;
        margin-right: auto;
        width: 500px;
    }

    .login-window {
        background-color: rgba(50, 50, 50, 0.3);
        padding: 5px 25px 40px 25px;
        position: absolute;
    }

    .loader-window {
        text-align: center;
        padding-top: 40px;
        position: absolute;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }

    .application-name {
        font-size: 140px;
        text-align: center;
        color: rgba(50, 50, 50, 0.3);
        letter-spacing: 10px;
        margin-bottom: -50px;
    }

    .application-description {
        font-size: 30px;
        text-align: center;
        color: rgba(50, 50, 50, 0.5);
        padding-bottom: 40px;
    }

    .login-window input {
        background-color: rgba(195, 195, 195, 0.3);
        color: rgba(255, 255, 255, 0.9);
        padding: 8px;
        border: 0 solid #FFF;
        width: 100%;
    }

    .login-error {
        border: 2px solid rgba(200, 20, 20, 0.6) !important;
    }

    .login-window input:focus {
        outline: none;
    }

    .login-window label {
        font-size: 14px;
        margin-top: 25px;
        color: rgba(255, 255, 255, 0.7);
        letter-spacing: 2px;
    }

    .login-error-msg {
        color: rgba(250, 20, 20, 0.6);
        text-align: center;
        font-size: 16px;
    }

    .login-submit {
        margin-top: 60px;
        cursor: pointer;
        text-align: center;
        padding: 10px;
        background-color: rgba(105, 105, 105, 0.4);
        color: rgba(255, 255, 255, 0.9);
        letter-spacing: 1px;
    }

    .login-submit:hover {
        background-color: rgba(105, 105, 105, 0.5);
    }
</style>
