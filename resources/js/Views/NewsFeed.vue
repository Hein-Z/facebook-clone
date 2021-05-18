<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar />

            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <NewPost />
                    <Post
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                        :author_profile_image="post.user.profile_image"
                        :author_id="post.user.id"
                        :author_name="post.user.name"
                    />
                </div>
                <infinite-loading
                    @infinite="infiniteHandler"
                    spinner="waveDots"
                    v-if="current_page"
                >
                    <div slot="no-more">No more post</div>
                </infinite-loading>
            </div>
        </div>
    </div>
</template>

<script>
import NewPost from "../components/NewPost";
import Post from "../components/Post";
import Sidebar from "../components/Sidebar";
import Nav from "../components/Nav";
import { mapActions, mapGetters, mapMutations } from "vuex";
import InfiniteLoading from "vue-infinite-loading";

export default {
    name: "NewsFeed",
    components: {
        NewPost,
        Post,
        Sidebar,
        Nav,
        InfiniteLoading
    },
    computed: {
        ...mapGetters({
            posts: "newsfeed/getPosts",
            current_page: "newsfeed/getCurrentPage",
            last_page: "newsfeed/getLastPage",
            next_page: "newsfeed/getNextPage"
        })
    },
    methods: {
        ...mapMutations({
            setPosts: "newsfeed/SET_POSTS",
            setCurrentPage: "newsfeed/SET_CURRENT_PAGE",
            setLastPage: "newsfeed/SET_LAST_PAGE",
            pushNewPosts: "newsfeed/PUSH_NEW_POSTS"
        }),
        ...mapActions({
            fetchPosts: "newsfeed/fetchPosts"
        }),
        infiniteHandler($state) {
            if (this.next_page > this.last_page) {
                $state.complete();
                return;
            }
            axios.get("/news-feed?page=" + this.next_page).then(response => {
                this.pushNewPosts(response.data.data);
                this.setCurrentPage(response.data.current_page);
                this.setLastPage(response.data.last_page);
                $state.loaded();
            });
        },
        refresh(loaded) {
            this.fetchData().then(_ => loaded("done"));
        },
        fetchData() {
            return new Promise((resolve, reject) => {
                this.fetchPosts()
                    .then(response => {
                        this.setPosts(response.data.data);
                        this.setCurrentPage(response.data.current_page);
                        this.setLastPage(response.data.last_page);
                        resolve("done");
                    })
                    .catch(err => {
                        if (err.status === 401) {
                            this.$toast.warning("Please login your account");
                            return this.$router.push({ name: "login" });
                        }
                        if (err.data.message) {
                            this.$toast.error(err.data.message);
                        }
                    });
            });
        }
    },
    created() {
        this.fetchData();
    }
};
</script>

<style scoped></style>
