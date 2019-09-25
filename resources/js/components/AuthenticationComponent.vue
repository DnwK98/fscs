<template>
    <div class="auth-main">

        <div class="application-name center-windows">
            FSCS
        </div>
        <div class="application-description center-windows">
            Counter Strike Server Automation
        </div>
        <div class="login-window center-windows">
            <label>Login</label><br />
            <input type="text" name="login" autocomplete="off"> <br />

            <label >Password</label><br />
            <input type="password" name="password" autocomplete="off">

            <div class="login-submit">Login</div>
        </div>

    </div>
</template>

<script>
    import Vue from 'vue'
    import store from "../store";


    export default Vue.component('authentication', {
        mounted() {

        }
    })
</script>

<style scoped>

    .auth-main {
        font-family: 'Nunito', sans-serif;
        position: absolute;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background-color: #600090;
    }

    .center-windows {
        margin-left: auto;
        margin-right: auto;
        width: 500px;
    }

    .login-window {
        background-color: rgba(50, 50, 50, 0.3);
        padding: 5px 25px 40px 25px;
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
        background-color: rgba(195,195,195, 0.3);
        color: rgba(255,255,255, 0.9);
        padding: 8px;
        border: 0 solid #FFF;
        width: 100%;

    }
    .login-window input:focus {
        outline: none;
    }

    .login-window label {
        font-size: 14px;
        margin-top: 25px;
        color: rgba(255,255,255, 0.7);
        letter-spacing: 2px;
    }

    .login-submit {
        margin-top: 60px;
        cursor: pointer;
        text-align: center;
        padding:10px;
        background-color: rgba(105,105,105, 0.4);
        color: rgba(255,255,255, 0.9);
        letter-spacing: 1px;
    }

    .login-submit:hover {
        background-color: rgba(105,105,105, 0.5);
    }
</style>
