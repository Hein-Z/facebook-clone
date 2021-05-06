<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav/>
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar/>

            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <NewPost/>
                    <Post v-for="new_post in new_posts"
                          :key="new_post.id"
                          :post="new_post"
                    />
                </div>
                <infinite-loading @infinite="infiniteHandler" spinner="waveDots" v-if="current_page">
                    <div slot="no-more">No more post</div>
                </infinite-loading>
            </div>

        </div>
    </div>
</template>

<script>
import NewPost from '../components/NewPost';
import Post from '../components/Post';
import Sidebar from "../components/Sidebar";
import Nav from "../components/Nav";
import {mapActions} from 'vuex';
import InfiniteLoading from 'vue-infinite-loading';


export default {
    name: "NewsFeed",
    components: {
        NewPost,
        Post,
        Sidebar,
        Nav,
        InfiniteLoading,

    },
    data() {
        return {
            'new_posts': [],
            'current_page': '',
            'last_page': '',
        }
    },
    methods: {
        ...mapActions({
            'fetchPosts': 'newsfeed/fetchPosts'
        }),
        infiniteHandler($state) {
            var next_page = this.current_page + +1;
            if (next_page > this.last_page) {
                $state.complete();
                return
            }
            axios.get('/news-feed?page=' + next_page)
                .then(response => {
                    this.new_posts.push(...response.data.data);
                    this.current_page = response.data.current_page;
                    this.last_page = response.data.last_page;
                    $state.loaded();
                });
        },
        refresh(loaded) {
            this.fetchData().then(_ => loaded('done'));
        },
        fetchData() {
            return new Promise((resolve, reject) => {
                    this.fetchPosts().then(response => {
                        this.new_posts = response.data.data;
                        this.current_page = response.data.current_page;
                        this.last_page = response.data.last_page;
                        resolve('done');
                    }).catch(err => {
                        if (err.response.status === 401) {
                            this.$toast.warning('Please login your account')
                            return this.$router.push({name: 'login'});
                        }
                        if (err.response.data.message) {
                            this.$toast.error(err.message);
                        }
                    });
                }
            )
        },
    },
    created() {
        this.fetchData();
    }
}
</script>

<style scoped>

</style>
