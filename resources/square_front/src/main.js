import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import './style.css'
import {createRouter, createWebHashHistory} from 'vue-router';
import {createPinia} from "pinia";

const routes = [
    {
        path: '/friends',
        name: 'friends',
        component: Friends,
        props: true
    },
    {
        path: '/home',
        name: 'home',
        component: Index,
        props: true
    },
    {
        path: '/',
        redirect: {name: 'home'},
        props: true
    },
    {
        path: '/notifications',
        name: 'notifications',
        component: Notifications,
        props: true
    },
    {
        path: '/people',
        name: 'people',
        component: People,
        props: true
    },
    {
        path: '/photos',
        name: 'photos',
        component: Photos,
        props: true
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        props: true
    }
]

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

const pinia = createPinia();

import {faEye} from "@fortawesome/free-regular-svg-icons";
import {faHeart} from "@fortawesome/free-solid-svg-icons";
import Friends from "./Pages/Home/Friends.vue";
import Index from "./Pages/Home/Index.vue";
import Notifications from "./Pages/Home/Notifications.vue";
import People from "./Pages/Home/People.vue";
import Photos from "./Pages/Home/Photos.vue";
import Profile from "./Pages/Home/Profile.vue";

library.add(faEye, faHeart)

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue')
        return pages[`./Pages/${name}.vue`]()
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin).use(pinia)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el)
    },
})
