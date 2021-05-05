class AppStorage {
    storeToken(token) {
        localStorage.setItem('token', token)
    }

    storeUser(user) {
        localStorage.setItem('email', user.email);
        localStorage.setItem('name', user.name);
        localStorage.setItem('user_id', user.id);
    }

    clearUser() {
        localStorage.removeItem('email');
        localStorage.removeItem('name');
        localStorage.removeItem('user_id');
    }

    getUser() {
        return {
            'email': localStorage.getItem('email'),
            'name': localStorage.getItem('name'),
            'id': localStorage.getItem('user_id')
        };
    }

    storeExpiresIn(second) {
        localStorage.setItem('expires_in', second)
    }

    clearToken() {
        localStorage.removeItem('token');
    }

    clearExpiresIn() {
        localStorage.removeItem('expires_in');
    }

    getToken() {
        return localStorage.getItem('token');
    }

    getExpiredIn() {
        return localStorage.getItem('expires_in');
    }

    clear() {
        this.clearToken();
        this.clearExpiresIn();
    }

}

export default new AppStorage();
