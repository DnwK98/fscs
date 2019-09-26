<script>
    import Vue from 'vue'
    import store from '../store'
    import axios from 'axios'

    export default  {}

    export function apiGet(request) {
        return $.ajax({
            type: "GET",
            url: request.url,
            data: request.data,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + store.state.auth.userToken);
            },
        })
            .fail(function (response) {
                console.log(response);
                if (response.status === 401) {
                    store.commit("tokenExpired");
                }
            })
    }

    export function apiPost(request) {
        return $.ajax({
            type: "POST",
            url: request.url,
            data: request.data,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + store.state.auth.userToken);
            },
        })
            .fail(function (response) {
                console.log(response);
                if (response.status === 401) {
                    store.commit("tokenExpired");
                }
            })
    }

</script>