import axios from "axios";
import AppStorage from "../../helper/AppStorage";

export default {
    namespaced: true,
    state: {
        user: {
            id: '',
            email: '',
            name: '',
            email_verified_at: '',
            profile_image: '',
            cover_photo: ''
        },

    },
    getters: {
        getUser: (state) => {
            return state.user
        },
        getProfileImage: (state) => {
            return state.user.profile_image;
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


    },
    mutations: {
        SET_USER(state, user) {
            AppStorage.storeUser(user)
            state.user.email = user.email;
            state.user.name = user.name;
            state.user.id = user.id;
            state.user.profile_image = user.profile_image;
            state.user.cover_photo = user.cover_photo;
        },
        SET_PROFILE_IMAGE(state, profile_image) {
            state.user.profile_image = profile_image
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
        }
    },
    actions: {
        clearStorage({ commit }) {
            commit('CLEAR_TOKEN')
            commit('CLEAR_USER')
            commit('CLEAR_EXPIRES_IN')
        },
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
        refreshToken({ commit, dispatch }) {
            return axios.post("auth/refresh")
                .then(res => {
                    commit('SET_USER', res.data.user);
                    commit('SET_TOKEN', res.data.access_token);
                    commit('SET_EXPIRES_IN', res.data.expires_in);
                    axios.defaults.headers.common["Authorization"] = 'Bearer ' +
                        res.data.access_token;
                    return res
                })
                .catch(err => {
                    dispatch('clearStorage');
                    throw err.response;
                });
        },
        logout({ commit, dispatch }) {
            return axios.post('auth/logout').then(response => {
                dispatch('clearStorage');
                return response
            }).catch(error => {
                throw error.response
            });
        },
        register({ commit, state, dispatch }, user) {
            return axios.post('auth/register', {
                email: user.email,
                name: user.name,
                password: user.password,
                password_confirmation: user.password_confirmation
            }).then(response => {
                commit('SET_USER', user);
                return response
            }).catch(error => {
                dispatch('clearStorage');
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
        },
        fetchAuthUser({ commit, state, dispatch }) {
            return axios.post('auth/me').then(res => {
                commit('SET_USER', res.data);
                return res
            }).catch(err => {
                dispatch('errorProcess', err);
                throw err.response
            });
        },
        errorProcess({ dispatch }, err) {
            if (err.response.status === 401) {
                dispatch('clearStorage');
            }
        }
    }
}
