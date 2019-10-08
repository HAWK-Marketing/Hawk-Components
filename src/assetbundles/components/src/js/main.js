import Vue from 'vue'
import VueRouter from 'vue-router'

import App from './App';
import ListAll from './ListAll';
import ListAtoms from './ListAtoms';

Vue.config.productionTip = false

const router = new VueRouter({
    routes: [
        {
            path: '/', component: ListAll,
        },
        {
            path: '/atoms', component: ListAtoms
        }
    ]
})

Vue.use(VueRouter);

new Vue({
    el: '#app',
    router,
    render: h => h(App)
})