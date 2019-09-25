import VueRouter from 'vue-router'

import Example from "./components/ExampleComponent";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'other',
            component: Example
        },
    ],
});

export default router;
