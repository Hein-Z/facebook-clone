<template>
    <div class="w-full flex justify-center h-full">
        <div class="w-full max-w-xs bg-white mt-6 flex flex-col justify-center">
            <svg
                class="fill-current w-16 h-16 self-center"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
            >
                <path
                    d="M23 12.1c0-6.1-4.9-11-11-11S1 6 1 12.1c0 5.5 4 10.1 9.3 10.9v-7.7H7.5v-3.2h2.8V9.7c0-2.8 1.6-4.3 4.2-4.3 1.2 0 2.5.2 2.5.2v2.7h-1.4c-1.4 0-1.8.8-1.8 1.7v2.1h3.1l-.5 3.2h-2.6V23c5.2-.9 9.2-5.4 9.2-10.9z"
                    fill="#1877f2"
                />
            </svg>

            <form
                class="shadow-md rounded px-8 pt-6 pb-8 mb-4"
                @submit.prevent="submit"
            >
                <div class="mb-4">
                    <label
                        class="block text-gray-700 text-sm font-bold mb-2"
                        for="email"
                    >
                        Email
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="email"
                        placeholder="Email"
                        id="email"
                        v-model="user.email"
                    />
                </div>
                <div class="mb-6">
                    <label
                        class="block text-gray-700 text-sm font-bold mb-2"
                        for="password"
                    >
                        Password
                    </label>
                    <input
                        class="shadow appearance-none border  rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        type="password"
                        placeholder="******************"
                        v-model="user.password"
                        id="password"
                    />
                    <p class="text-red-500 text-xs italic">{{ feedback }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit"
                    >
                        Sign In
                    </button>
                    <router-link
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                        :to="{ name: 'forgotPassword' }"
                    >
                        Forgot Password?
                    </router-link>
                </div>
                <div class="mt-2 flex justify-center">
                    Do not have account?
                    <router-link
                        :to="{ name: 'register' }"
                        class="font-bold ml-2 text-blue-500 hover:text-blue-800"
                    >
                        Register
                    </router-link>
                </div>
            </form>
            <p class="text-center text-gray-500 text-xs">
                &copy;2020 Acme Corp. All rights reserved.
            </p>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
import { mapMutations } from "vuex";

export default {
    data: () => {
        return {
            user: {
                email: "",
                password: ""
            },
            feedback: ""
        };
    },
    methods: {
        ...mapActions({
            login: "auth/login"
        }),
        ...mapMutations({
            setToken: "auth/SET_TOKEN",
            setExpiresIn: "auth/SET_EXPIRES_IN",
            setUser: "auth/SET_USER"
        }),
        submit() {
            this.login(this.user)
                .then(res => {
                    this.$router.push({ name: "home" });
                })
                .catch(error => {
                    if (
                        error.data.correct_credential &&
                        !error.data.email_verified
                    ) {
                        return this.$router.push({ name: "verifyEmail" });
                    }
                    if (error.data.message) {
                        this.$toast.warning(error.data.message);
                    }
                });
        }
    }
};
</script>
