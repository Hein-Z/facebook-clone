<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar />

            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-10">
                            <div class="w-100 h-64 overflow-hidden z-10">
                                <img
                                    :src="coverImage"
                                    alt="post image"
                                    class="object-cover w-full rounded-lg"
                                />
                            </div>

                            <div
                                class="absolute flex items-center bottom-0 left-0 -mb-14 ml-12 z-20"
                            >
                                <div
                                    class="w-32 h-32 rounded-full overflow-hidden border-4"
                                >
                                    <img
                                        :src="profileImage"
                                        alt="Profile Image"
                                        class="shadow w-full h-full align-middle border-blue-400 border-solid  object-cover"
                                    />
                                </div>

                                <p
                                    class="text-2xl -mb-14 text-gray-700 ml-4 font-extrabold"
                                >
                                    {{ user.name }}
                                </p>
                            </div>

                            <friend-btn
                                :friendshipStatus="friendshipStatus"
                                @update="setFriendshipStatus"
                                v-if="friendshipStatus !== 'auth user'"
                            ></friend-btn>
                        </div>

                        <div v-if="isLoading">
                            Loading posts...
                        </div>

                        <div v-else-if="posts.length < 1">
                            No posts found. Get started...
                        </div>

                        <Post
                            v-else
                            v-for="(post, postKey) in posts"
                            :key="postKey"
                            :post="post"
                            :author_id="user.id"
                            :author_name="user.name"
                            :author_profile_image="user.profile_image"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Post from "../../components/Post";
import Nav from "../../components/Nav";
import Sidebar from "../../components/Sidebar";
import FriendBtn from "../../components/FriendBtn";
import Token from "../../helper/Token";
import AppStorage from "../../helper/AppStorage";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
    name: "profile",
    data() {
        return {
            isLoading: false
        };
    },
    components: {
        Post,
        Nav,
        Sidebar,
        FriendBtn
    },
    computed: {
        ...mapGetters({
            friendshipStatus: "profile/getFriendshipStatus",
            posts: "profile/getPosts",
            user: "profile/getUser"
        }),
        profileImage() {
            const profileImage = this.user.profile_image;
            const defaultImage = process.env.MIX_APP_URL + "/default.jpg";

            return this.user.profile_image ? profileImage : defaultImage;
        },
        coverImage() {
            const profileImage = this.user.cover_photo;
            const defaultImage = process.env.MIX_APP_URL + "/default.jpg";

            return this.user.cover_photo ? profileImage : defaultImage;
        }
    },
    methods: {
        ...mapActions({
            fetchUser: "profile/fetchUser"
        }),
        ...mapMutations({
            setUser: "profile/SET_USER",
            setFriendshipStatus: "profile/SET_FRIENDSHIP_STATUS",
            setPosts: "profile/SET_POSTS",
            setCurrentPage: "profile/SET_CURRENT_PAGE",
            setLastPage: "profile/SET_LAST_PAGE"
        }),
        getUser(user_id) {
            this.fetchUser(user_id)
                .then(res => {
                    this.setUser(res.data.user);
                    this.setPosts(res.data.user.posts.data);
                    this.setLastPage(res.data.user.posts.last_page);
                    this.setCurrentPage(res.data.user.posts.current_page);
                })
                .catch(err => {
                    if (err.status === 401) {
                        this.$toast.warning("Please login your account");
                        return this.$router.push({ name: "login" });
                    }
                    if (err.data.message) {
                        this.$toast.error(err.data.message);
                        return this.$router.back();
                    }
                })
                .finally(_ => (this.isLoading = false));
        }
    },
    created() {
        this.isLoading = true;
        let user_id = this.$route.params.user_id;

        this.getUser(user_id);
    }
};
</script>
