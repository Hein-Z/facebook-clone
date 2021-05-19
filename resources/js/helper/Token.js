class Token {
    payload(token) {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace('-', '+').replace('_', '/');
        // console.log(this.decode(base64));
        return this.decode(base64);
    }

    decode(payload) {
        return JSON.parse(atob(payload));
    }

    isValid(token) {
        if (token) {
            var payload = this.payload(token);
            if (payload) {
                return (payload.iss === process.env.MIX_APP_URL + "/api/auth/login") || (payload.iss === process.env.MIX_APP_URL + "/api/auth/refresh");
            }
        }
        return false;
    }

    isExpired(token) {
        if (token) {
            var payload = this.payload(token);
            if (payload) {
                return payload.exp < ((new Date().getTime() / 1000) + 1800);
            }
        }
        return false;

    }

    expiresIn(token) {
        if (token) {
            var payload = this.payload(token);
            if (payload)
                return payload.exp;
        }
        return false;
    }
}
export default new Token();
