require('./bootstrap');
import Vue from 'vue'
import PhotoGrid from 'vue-photo-grid';
Vue.use(PhotoGrid);



import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'
Vue.use(CoolLightBox)

Vue.mixin({
    methods: {
        reverse: function (value) {
            return value.slice().reverse();
        },
    },
})

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




import { router } from "./route";

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
    components: { App },
});

