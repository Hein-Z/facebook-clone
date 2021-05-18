import axios from "axios"

export default {
    namespaced: true,
    state: {
        posts: [],
        current_page: '',
        last_page: '',
        user: {}
    },
    mutations: {
        SET_POSTS(state, posts) {
            state.posts = posts
        },
        SET_FRIENDSHIP_STATUS(state, status) {
            state.user.friendshipStatus = status
        },
        SET_CURRENT_PAGE(state, current_page) {
            state.current_page = current_page
        },
        SET_LAST_PAGE(state, last_page) {
            state.last_page = last_page
        },
        PUSH_NEW_POSTS(state, new_posts) {
            state.posetLastPagests.push(...new_posts)
        },
        SET_USER(state, user) {
            state.user = user
        }
    },
    getters: {
        getPosts(state) {
            return state.posts
        },

        getCurrentPage(state) {
            return state.current_page
        },
        getLastPage(state) {
            return state.last_page
        },
        getNextPage(state) {
            return (state.current_page + +1);
        },
        getUser(state) {
            return state.user
        },
        getFriendshipStatus(state) {
            return state.user.friendshipStatus;
        }
    },
    actions: {
        fetchUser({ commit, dispatch }, user_id) {
            return axios.get(`users/${user_id}/profile`).then(res => res).catch(err => {
                if (err.response.status === 401) {
                    dispatch('auth/clearStorage', {}, { root: true });
                }
                throw (err.response)
            }).finally();
        },
        addFriend({ commit, dispatch, state }) {
            return axios.post(`users/${state.user.id}/add-friend`).then(res => res).catch(err => {
                if (err.response.status === 401) {
                    dispatch('auth/clearStorage', {}, { root: true });
                }
                throw (err.response)
            }).finally();
        },
        acceptFriendRequest({ commit, dispatch, state }, user_id) {
            return axios.post(`users/${state.user.id}/accept-friend-request`).then(res => res).catch(err => {
                if (err.response.status === 401) {
                    dispatch('auth/clearStorage', {}, { root: true });
                }
                throw (err.response)
            }).finally();
        },
        ignoreFriendRequest({ commit, dispatch, state }, user_id) {
            return axios.post(`users/${state.user.id}/ignore-friend-request`).then(res => res).catch(err => {
                if (err.response.status === 401) {
                    dispatch('auth/clearStorage', {}, { root: true });
                }
                throw (err.response)
            }).finally();
        },
        unfriend({ commit, dispatch, state }) {
            return axios.post(`users/${state.user.id}/unfriend`).then(res => res).catch(err => {
                if (err.response.status === 401) {
                    dispatch('auth/clearStorage', {}, { root: true });
                }
                throw (err.response)
            }).finally();
        },
        cancelFriendRequest({ commit, dispatch, state }) {
            return axios.post(`users/${state.user.id}/cancel-friend-request`).then(res => res).catch(err => {
                if (err.response.status === 401) {
                    dispatch('auth/clearStorage', {}, { root: true });
                }
                throw (err.response)
            }).finally();
        }
    }
}
