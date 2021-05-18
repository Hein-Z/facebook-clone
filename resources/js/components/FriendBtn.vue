<template>
    <div>
        <div
            class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20"
            v-if="friendshipStatus === 'not friend'"
        >
            <button
                class="py-1 px-3 bg-gray-400 rounded"
                @click="addFriendProcess"
            >
                Add Friend
            </button>
        </div>

        <div
            class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20"
            v-else-if="friendshipStatus === 'friend'"
        >
            <button
                class="py-1 px-3 bg-blue-400 rounded"
                @click="unfriendProcess"
            >
                Friend
            </button>
        </div>

        <div
            class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20"
            v-else-if="friendshipStatus === 'pending'"
            @click="cancelFriendRequestProcess"
        >
            <button class="py-1 px-3 bg-gray-400 rounded">
                Friend Request Sent
            </button>
        </div>

        <div
            class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20"
            v-else-if="friendshipStatus === 'get request'"
        >
            <button
                class="mr-2 py-1 px-3 bg-blue-500 rounded"
                @click="acceptFriendRequestProcess"
            >
                Accept
            </button>
            <button
                class="py-1 px-3 bg-gray-400 rounded"
                @click="ignoreFriendRequestProcess"
            >
                Ignore
            </button>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
    props: ["friendshipStatus"],
    data() {
        return {
            loading: false
        };
    },
    methods: {
        ...mapActions({
            unfriend: "profile/unfriend",
            addFriend: "profile/addFriend",
            acceptFriendRequest: "profile/acceptFriendRequest",
            ignoreFriendRequest: "profile/ignoreFriendRequest",
            cancelFriendRequest: "profile/cancelFriendRequest"
        }),
        unfriendProcess() {
            if (!confirm("do you want to unfriend")) {
                return;
            }
            if (this.friendshipStatus !== "friend" && !this.loading) {
                return;
            }
            this.loading = true;
            this.unfriend()
                .then(res => {
                    this.$emit("update", "not friend");
                    // this.$toast.success(res.data.message);
                })
                .catch(err => this.errorProcess(err))
                .finally(_ => (this.loading = true));
        },
        addFriendProcess() {
            if (this.friendshipStatus !== "not friend" && !this.loading) {
                return;
            }
            this.loading = true;

            this.addFriend()
                .then(res => {
                    this.$emit("update", "pending");
                    // this.$toast.success(res.data.message);
                })
                .catch(err => this.errorProcess(err))
                .finally(_ => (this.loading = true));
        },
        ignoreFriendRequestProcess() {
            if (this.friendshipStatus !== "get request" && !this.loading) {
                return;
            }
            this.loading = true;

            this.ignoreFriendRequest()
                .then(res => {
                    this.$emit("update", "not friend");
                    // this.$toast.success(res.data.message);
                })
                .catch(err => this.errorProcess(err))
                .finally(_ => (this.loading = true));
        },
        acceptFriendRequestProcess() {
            if (this.friendshipStatus !== "get request" && !this.loading) {
                return;
            }
            this.loading = true;

            this.acceptFriendRequest()
                .then(res => {
                    this.$emit("update", "friend");
                    // this.$toast.success(res.data.message);
                })
                .finally(_ => (this.loading = true))
                .catch(err => this.errorProcess(err));
        },
        cancelFriendRequestProcess() {
            if (this.friendshipStatus !== "pending" && !this.loading) {
                return;
            }
            this.loading = true;

            this.cancelFriendRequest()
                .then(res => {
                    this.$emit("update", "not friend");
                    // this.$toast.success(res.data.message);
                })
                .catch(err => this.errorProcess(err))
                .finally(_ => (this.loading = true));
        },
        errorProcess(err) {
            if (err.friendshipStatus === 401) {
                this.$toast.warning("Please login your account");
                return this.$router.push({ name: "login" });
            }
            if (err.data.message) {
                this.$toast.error(err.data.message);
            }
        }
    }
};
</script>
