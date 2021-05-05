import Token from "./Token";
import AppStorage from './AppStorage'

class User {

    responseAfterLogin(res) {
        const token = res.data.access_token;
        const user = res.data.user;

        if (Token.isValid(token)) {
            AppStorage.store(user, token);
            window.axios.defaults.headers.common['Authorization'] ='Bearer '+ token;
        }

    }

    checkToken() {
        const token = AppStorage.getToken();
        if (token) {
            return Token.isValid(token)
        }
        return false
    }

    isLoggedIn() {
        return this.checkToken();
    }

    logout() {
        AppStorage.clear();
    }

    name() {
        if (this.isLoggedIn()) {
            return AppStorage.getUser();
        }
        return null;
    }

    id() {
        if (this.isLoggedIn()) {
            const payload = Token.payload(AppStorage.getToken());
            return payload.sub;
        }
        return null;
    }
}

export default new User();
