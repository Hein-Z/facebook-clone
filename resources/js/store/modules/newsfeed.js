export default {
    namespaced: true,
    actions: {
        fetchPosts({dispatch, commit}) {
            return axios.get('news-feed').then(res => res).catch(err => {
                if (err.response.status === 401) {
                    commit('auth/CLEAR_USER', {}, {root: true});
                    commit('auth/CLEAR_TOKEN', {}, {root: true});
                    commit('auth/CLEAR_EXPIRES_IN', {}, {root: true});
                }
                throw err
            });
        }
    }
}
