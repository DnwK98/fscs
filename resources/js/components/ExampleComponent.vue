<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" @click="auth.userToken=false">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { apiGet, apiPost } from './ApiComponent'
    import store from "../store";
    import {mapState} from 'vuex'
    export default {
        mounted() {
            setTimeout(function () {
                apiGet({url: "api/v1/user"})
            }, 10000);
        },
        computed: mapState([
            "auth"
        ]),
    }
</script>
