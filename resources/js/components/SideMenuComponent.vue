<template>
    <div class="menu-container">
        <div class="menu">
            <div class="logo">
                F<br/>
                S<br/>
                C<br/>
                S<br/>
            </div>
        </div>
        <div class="route-links">
            hello
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'

    export default Vue.component('side-menu', {
        mounted() {

        }
    });
</script>

<style scoped lang="scss">

    @import "../../sass/_variables.scss";

    .menu {
        height: 1000px;
        width: 50px;
        background-color: $mainColor;
        float:left;
    }

    .route-links {
        background-color: #CCCCCC;
        width: 400px;
        height: 1000px;
        border-right: 1px solid rgba(0,0,0,0.3);
    }

    .logo {
        padding: 10px;
        width: 100%;
        text-align: center;
        color: rgba(0, 0, 0, 0.3);
        font-size: 20px;
        font-weight: bold;
        line-height: 1.4;
    }

</style>
