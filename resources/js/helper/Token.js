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
                return (payload.iss === "http://127.0.0.1:8000/api/auth/login");
            }
        }
        return false;
    }

}

export default new Token();
