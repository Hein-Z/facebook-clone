import Vue from "vue";
import VueRouter from "vue-router";
import NewsFeed from "../Views/NewsFeed";
import Login from "../Views/Auth/Login";
import Register from "../Views/Auth/Register";
import ResetPassword from "../Views/Auth/ResetPassword";
import VerifyEmail from '../Views/Auth/VerifyEmail'
import ForgotPassword from "../Views/Auth/ForgotPassword";
import Token from "../helper/Token";
import AppStorage from "../helper/AppStorage";


Vue.use(VueRouter);

const routes = [
    {
        name: 'home',
        path: '/',
        component: NewsFeed,
        meta: {requiresAuth: true}
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {requiresAuth: false}
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
        meta: {requiresAuth: false}
    },
    {
        name: 'response-password-reset',
        path: '/response-password-reset',
        component: ResetPassword,
        meta: {requiresAuth: false}
    },
    {
        name: 'verifyEmail',
        path: '/verify-email',
        component: VerifyEmail,
        meta: {requiresAuth: false}
    },
    {
        name: 'forgotPassword',
        path: '/forgot-password',
        component: ForgotPassword,
        meta: {requiresAuth: false},
    },
    {
        name: 'passwordResetform',
        path: '/password-reset-form',
        component: ResetPassword,
        meta: {requiresAuth: false},
    }
]

export const router = new VueRouter({
    routes,
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!Token.isValid(AppStorage.getToken())) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else {
        if (Token.isValid(AppStorage.getToken())) {
            next({
                name: 'home',
            })
        } else {
            next()
        }
    }
})

