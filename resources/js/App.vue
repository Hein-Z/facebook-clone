<template>
    <div>
        <router-view></router-view>
    </div>
</template>
<script>
import { mapActions, mapMutations } from "vuex";
export default {
    name: "app",
    methods: {
        ...mapActions({
            fetchAuthUser: "auth/fetchAuthUser"
        })
    },
    created() {
        this.fetchAuthUser()
            .then(res => res)
            .catch(err => {
                if (err.status === 401) {
                    this.$toast.warning("Please login your account");
                    return this.$router.push({ name: "login" });
                }
                if (err.data.message) {
                    this.$toast.error(err.response.data.message);
                }
            });
    }
};
</script>
