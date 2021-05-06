require('./bootstrap');
import Vue from 'vue'

import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";

const options = {
    position: "top-center",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.82,
    showCloseButtonOnHover: true,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
};


Vue.use(Toast, options);

import {router} from "./route";

import App from './App';


//support vuex
import Vuex from 'vuex'


Vue.use(Vuex)


import storeData from "./store/index"

const store = new Vuex.Store(
    storeData
)

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {App},
});

