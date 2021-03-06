import axios from "axios"

export default {
    namespaced: true,
    state: {
        posts: [],
        current_page: '',
        last_page: '',
    },
    mutations: {
        SET_POSTS(state, posts) {
            state.posts = posts
        },
        SET_CURRENT_PAGE(state, current_page) {
            state.current_page = current_page
        },
        SET_LAST_PAGE(state, last_page) {
            state.last_page = last_page
        },
        PUSH_NEW_POSTS(state, new_posts) {
            state.posts.push(...new_posts)
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

        }
    },
    actions: {
        fetchPosts({ dispatch, commit }) {
            return axios.get('news-feed').then(res =>
                res
            ).catch(err => {
                dispatch('auth/errorProcess', err, { root: true });
                throw err.response
            });
        },
        getPost({ commit, dispatch }, post_id) {
            return axios.get(`posts/${post_id}`)
                .then(res => res)
                .catch(err => {
                    dispatch('auth/errorProcess', err, { root: true });
                    throw err.response

                });
        },
        sendReaction({ commit, dispatch }, { post_id, type }) {
            return axios.post(`posts/${post_id}/add-react`, { type })
                .then(res => res)
                .catch(err => {
                    dispatch('auth/errorProcess', err, { root: true });
                    throw err.response

                });
        },
        removeReaction({ commit, dispatch }, post_id) {
            return axios.post(`posts/${post_id}/remove-react`)
                .then(res => res)
                .catch(err => {
                    dispatch('auth/errorProcess', err, { root: true });
                    throw err.response

                });
        },
        postComment({ commit, dispatch }, { post_id, comment }) {
            return axios.post(`posts/${post_id}/comments`, { comment }).then(res => res).catch(err => {
                dispatch('auth/errorProcess', err, { root: true });
                throw err.response

            });
        }
    }
}
