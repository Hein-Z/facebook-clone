import AppStorage from "../../helper/AppStorage";

export default {
    namespaced: true,
    state: {
        user: {
            id: '',
            email: '',
            name: '',
            email_verified_at: '',
        },

    },
    getters: {
        getUser: (state) => {
            return state.user
        },
        getUserId: (state) => {
            return state.user.id;
        },

        getUserEmail: (state) => {
            return state.user.email
        },

        getUserName: (state) => {
            return state.user.name
        },

        isUserVerify: (state) => {
            return !!state.user.email_verified_at;
        },

        isPasswordConfirmed: (state) => {
            return state.user.password === this.state.user.password_confirmation
        },

    },
    mutations: {
        SET_USER(state, user) {
            AppStorage.storeUser(user)
            state.user.email = user.email;
            state.user.name = user.name;
            state.user.id = user.id;
        },
        SET_TOKEN(state, token) {
            AppStorage.storeToken(token);
        },
        SET_EXPIRES_IN(state, second) {
            AppStorage.storeExpiresIn(second);
        },
        CLEAR_TOKEN() {
            AppStorage.clearToken();
        },
        CLEAR_EXPIRES_IN() {
            AppStorage.clearExpiresIn();
        },
        CLEAR_USER(state) {
            state.user = { id: '', email: '', name: '' };
            AppStorage.clearUser();
        },
        CLEAR_STORAGE({ state, commit }) {
            commit('CLEAR_TOKEN')
            commit('CLEAR_USER')
            commit('CLEAR_EXPIRES_IN')
        }
    },
    actions: {
        login({ commit, state }, user) {
            return axios.post('auth/login', { email: user.email, password: user.password }).then(response => {
                commit('SET_USER', response.data.user);
                commit('SET_TOKEN', response.data.access_token);
                commit('SET_EXPIRES_IN', response.data.expires_in);

                const token = 'Bearer ' + response.data.access_token;
                window.axios.defaults.headers.common['Authorization'] = token;
                return response
            }).catch(error => {
                commit('SET_USER', {})
                throw error.response
            })
        },
        logout({ commit }) {
            return axios.post('auth/logout').then(response => {
                commit('CLEAR_USER');
                commit('CLEAR_TOKEN');
                commit('CLEAR_EXPIRES_IN');
                return response
            }).catch(error => {
                throw error.response
            });
        },
        register({ commit, state }, user) {
            return axios.post('auth/register', {
                email: user.email,
                name: user.name,
                password: user.password,
                password_confirmation: user.password_confirmation
            }).then(response => {
                commit('SET_USER', user);
                return response
            }).catch(error => {
                commit('SET_USER', {})
                throw error.response
            })
        },
        verifyEmail({ commit, state }, verification_code) {
            return axios.post('auth/verifyUser', {
                email: AppStorage.getUser().email,
                verification_code
            }).then(response => {
                return response
            }).catch(error => {
                throw error.response
            })
        },
        resendCode({ commit, state }) {
            return axios.post('auth/sendVerificationCode', { email: AppStorage.getUser().email }).then(response => {
                return response
            }).catch(error => {
                throw error.response
            })
        },
        requestPasswordForm({ commit, state }, user) {
            return axios.post('auth/sendPasswordResetLink', { email: user.email }).then(res => {
                return res
            }).catch(err => {
                throw err.response
            });
        },
        resetPassword({ commit, state }, user) {
            return axios.patch('auth/resetPassword', {
                email: user.email,
                password: user.password,
                password_confirmation: user.password_confirmation,
                token: user.token
            }).then(res => {
                return res
            }).catch(err => {
                throw err.response
            })
        }
    }
}
