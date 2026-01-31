import {createRouter, createWebHistory} from "vue-router";
import Board from "./pages/Board.vue";
import Login from "./pages/Login.vue";
import Registration from "./pages/Registration.vue";
import Main from "./pages/Main.vue";

export const router = createRouter({
    history: createWebHistory(),
    routes: [
        {path: '/board/:hash?', component: Board, name: "Board"},
        {path: '/', component: Login, name: "Login"},
        {path: '/registration', component: Registration, name: "Registration"},
        {path: '/main', component: Main, name: "Main"},
    ]
})