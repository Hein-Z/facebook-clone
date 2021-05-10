<template>
    <div>
        <div class="flex justify-center">
            <button @click="loadComments">load more comment</button>
        </div>
        <comment
            v-for="comment in comments_data"
            :key="comment.id"
            :comment="comment"
        ></comment>
        <comment
            v-for="comment in added_comments"
            :key="comment.id"
            :comment="comment"
        ></comment>
    </div>
</template>
<script>
import { mapMutations } from "vuex";
import Comment from "../components/Comment";
export default {
    props: ["comments", "post_id", "added_comments"],
    data: () => {
        return {
            last_page: "",
            current_page: "",
            comments_data: {}
        };
    },
    computed: {
        next_page() {
            return this.current_page + 1;
        }
    },
    components: {
        Comment
    },
    methods: {
        ...mapMutations({
            clearStorage: "auth/CLEAR_STORAGE"
        }),
        loadComments() {
            if (this.next_page > this.last_page) {
                return;
            }
            axios
                .get(`posts/${this.post_id}/comments?page=` + this.next_page)
                .then(response => {
                    this.comments_data.unshift(...response.data.data);
                    this.current_page = response.data.current_page;
                    this.last_page = response.data.last_page;
                })
                .catch(err => {
                    if (err.response.status === 401) {
                        this.clearStorage();
                        this.$toast.warning("Please login your account");
                        return this.$router.push({ name: "login" });
                    }
                    if (err.response.data.message)
                        this.$toast.warning(err.response.data.message);
                });
        }
    },
    created() {
        this.comments_data = this.comments.data;
        this.current_page = this.comments.current_page;
        this.last_page = this.comments.last_page;
    }
};
</script>
