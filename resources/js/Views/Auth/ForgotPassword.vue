<template>


    <div class="w-full flex justify-center h-full">

        <div class="w-full max-w-xs bg-white mt-6 flex flex-col justify-center">

            <svg class="fill-current w-16 h-16 self-center" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M23 12.1c0-6.1-4.9-11-11-11S1 6 1 12.1c0 5.5 4 10.1 9.3 10.9v-7.7H7.5v-3.2h2.8V9.7c0-2.8 1.6-4.3 4.2-4.3 1.2 0 2.5.2 2.5.2v2.7h-1.4c-1.4 0-1.8.8-1.8 1.7v2.1h3.1l-.5 3.2h-2.6V23c5.2-.9 9.2-5.4 9.2-10.9z"
                    fill="#1877f2"/>
            </svg>

            <form class="shadow-md rounded px-8 pt-6 pb-8 mb-4" @submit.prevent="submit" v-if="!form_disable">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Enter Your Email
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="email" placeholder="Email" v-model="user.email">
                </div>
                <div class="flex items-center justify-between">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Enter
                    </button>

                    <div class="inline-block align-baseline flex flex-col">
                        <router-link class="font-bold text-sm text-blue-500 hover:text-blue-800 "
                                     :to="{name:'login'}">
                            Return To Login
                        </router-link>
                    </div>
                </div>
            </form>
            <div class="flex flex-col items-center">
                <router-link class="font-bold text-sm text-blue-500 hover:text-blue-800 " :to="{name:'login'}">
                    Return To Login
                </router-link>
            </div>
            <p class="text-center text-gray-500 text-xs">
                &copy;2020 Acme Corp. All rights reserved.
            </p>
        </div>
    </div>

</template>
<script>
import {mapActions, mapMutations, mapGetters} from 'vuex';

export default {
    data: () => {
        return {
            user: {
                email: '',
            },
            form_disable: false
        }
    },
    computed: {},
    methods: {

        ...mapActions({'requestResetPasswordForm': 'auth/requestPasswordForm'}),
        submit() {
            this.form_disable = true;
            this.requestResetPasswordForm(this.user).then(res => {
                    this.$toast.success(res.data.message)

                }
            ).catch(err => {
                this.form_disable = false
                this.$toast.error(err.data.message)
            });
        }
    }
}
</script>
