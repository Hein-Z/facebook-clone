<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar />

            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-8">
                            <div class="w-100 h-64 overflow-hidden z-10">
                                <img
                                    src="https://images.pexels.com/photos/132037/pexels-photo-132037.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                                    alt="post image"
                                    class="object-cover w-full rounded-lg"
                                />
                            </div>

                            <div
                                class="absolute flex items-center bottom-0 left-0 -mb-14 ml-12 z-20"
                            >
                                <div class="w-32 h-32 rounded-full overflow-hidden border-4">
                                        <img
                                            src="https://images.pexels.com/photos/132037/pexels-photo-132037.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                                            alt="Profile Image"
                                            class="shadow w-full h-full align-middle border-blue-400 border-solid  object-cover"
                                        />
                                </div>

                                <p
                                    class="text-2xl -mb-14 text-gray-700 ml-4 font-extrabold"
                                >
                                    User Name
                                </p>
                            </div>

                            <div
                                class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20"
                            >
                                <!-- <button class="py-1 px-3 bg-gray-400 rounded" >Add Friend</button> -->
                                <button
                                    class="mr-2 py-1 px-3 bg-blue-500 rounded"
                                >
                                    Accept
                                </button>
                                <button class="py-1 px-3 bg-gray-400 rounded">
                                    Ignore
                                </button>
                            </div>
                        </div>

                        <!-- <div v-if="status.posts === 'loading'">Loading posts...</div>

        <div v-else-if="posts.length < 1">No posts found. Get started...</div> -->

                        <!-- <Post v-else v-for="(post, postKey) in posts.data" :key="postKey" :post="post" /> -->
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

import { mapGetters } from "vuex";

export default {
    name: "profile",

    components: {
        Post,
        Nav,
        Sidebar
    }

    // mounted() {
    //     this.$store.dispatch('fetchUser', this.$route.params.userId);
    //     this.$store.dispatch('fetchUserPosts', this.$route.params.userId);
    // },

    // computed: {
    //     ...mapGetters({
    //         user: 'user',
    //         posts: 'posts',
    //         status: 'status',
    //         friendButtonText: 'friendButtonText',
    //     }),
    // }
};
</script>

<style scoped></style>
