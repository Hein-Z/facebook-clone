<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar />
            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <div class="bg-white rounded shadow w-2/3 mt-6 relative">
                        <div class="flex flex-col p-4">
                            <div class="flex items-center">
                                <router-link
                                    :to="{
                                        name: 'user-profile',
                                        params: {
                                            user_id: post.user
                                                ? post.user.id
                                                : null
                                        }
                                    }"
                                    tag="div"
                                    class="w-8 cursor-pointer"
                                >
                                    <img
                                        :src="profileImage"
                                        alt="profile image for user"
                                        class="w-8 h-8 object-cover rounded-full"
                                    />
                                </router-link>
                                <div class="ml-6">
                                    <router-link
                                        tag="div"
                                        class="text-sm font-bold cursor-pointer"
                                        :to="{
                                            name: 'user-profile',
                                            params: {
                                                user_id: post.user
                                                    ? post.user.id
                                                    : null
                                            }
                                        }"
                                    >
                                        {{ post.user ? post.user.name : "" }}
                                    </router-link>
                                    <div class="text-sm text-gray-600">
                                        {{ createdMoment }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p>{{ post.status }}</p>
                            </div>
                        </div>

                        <div class="w-full">
                            <img
                                src="https://images.pexels.com/photos/132037/pexels-photo-132037.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                                alt="post image"
                                class="w-full"
                            />
                        </div>

                        <div
                            class="px-4 pt-2 flex justify-between text-gray-700 text-sm"
                        >
                            <div class="flex">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    class="fill-current w-5 h-5"
                                >
                                    <path
                                        d="M20.8 15.6c.4-.5.6-1.1.6-1.7 0-.6-.3-1.1-.5-1.4.3-.7.4-1.7-.5-2.6-.7-.6-1.8-.9-3.4-.8-1.1.1-2 .3-2.1.3-.2 0-.4.1-.7.1 0-.3 0-.9.5-2.4.6-1.8.6-3.1-.1-4.1-.7-1-1.8-1-2.1-1-.3 0-.6.1-.8.4-.5.5-.4 1.5-.4 2-.4 1.5-2 5.1-3.3 6.1l-.1.1c-.4.4-.6.8-.8 1.2-.2-.1-.5-.2-.8-.2H3.7c-1 0-1.7.8-1.7 1.7v6.8c0 1 .8 1.7 1.7 1.7h2.5c.4 0 .7-.1 1-.3l1 .1c.2 0 2.8.4 5.6.3.5 0 1 .1 1.4.1.7 0 1.4-.1 1.9-.2 1.3-.3 2.2-.8 2.6-1.6.3-.6.3-1.2.3-1.6.8-.8 1-1.6.9-2.2.1-.3 0-.6-.1-.8zM3.7 20.7c-.3 0-.6-.3-.6-.6v-6.8c0-.3.3-.6.6-.6h2.5c.3 0 .6.3.6.6v6.8c0 .3-.3.6-.6.6H3.7zm16.1-5.6c-.2.2-.2.5-.1.7 0 0 .2.3.2.7 0 .5-.2 1-.8 1.4-.2.2-.3.4-.2.6 0 0 .2.6-.1 1.1-.3.5-.9.9-1.8 1.1-.8.2-1.8.2-3 .1h-.1c-2.7.1-5.4-.3-5.4-.3H8v-7.2c0-.2 0-.4-.1-.5.1-.3.3-.9.8-1.4 1.9-1.5 3.7-6.5 3.8-6.7v-.3c-.1-.5 0-1 .1-1.2.2 0 .8.1 1.2.6.4.6.4 1.6-.1 3-.7 2.1-.7 3.2-.2 3.7.3.2.6.3.9.2.3-.1.5-.1.7-.1h.1c1.3-.3 3.6-.5 4.4.3.7.6.2 1.4.1 1.5-.2.2-.1.5.1.7 0 0 .4.4.5 1 0 .3-.2.6-.5 1z"
                                    />
                                </svg>
                                <p>{{ reacts_count }}</p>
                            </div>

                            <div>
                                <p>{{ post.comments_count }} comments</p>
                            </div>
                        </div>

                        <div
                            class="flex justify-between border-1 border-gray-400 m-4"
                        >
                            <div
                                class="flex justify-center py-2 rounded-lg text-sm text-gray-700 w-full "
                            >
                                <reaction
                                    @onReact="reactPost"
                                    :user_react_type="user_react_type"
                                    @removeReact="removeReact"
                                ></reaction>
                            </div>
                            <div
                                class="flex justify-center py-2 rounded-lg text-sm text-gray-700 w-full hover:bg-gray-200 cursor-pointer"
                                @click="showCommentForm = !showCommentForm"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    class="fill-current w-7 h-7"
                                >
                                    <path
                                        d="M20.3 2H3.7C2 2 .6 3.4.6 5.2v10.1c0 1.7 1.4 3.1 3.1 3.1V23l6.6-4.6h9.9c1.7 0 3.1-1.4 3.1-3.1V5.2c.1-1.8-1.3-3.2-3-3.2zm1.8 13.3c0 1-.8 1.8-1.8 1.8H9.9L5 20.4V17H3.7c-1 0-1.8-.8-1.8-1.8v-10c0-1 .8-1.8 1.8-1.8h16.5c1 0 1.8.8 1.8 1.8v10.1zM6.7 6.7h10.6V8H6.7V6.7zm0 2.9h10.6v1.3H6.7V9.6zm0 2.8h10.6v1.3H6.7v-1.3z"
                                    />
                                </svg>
                                <p class="ml-2 text-lg">Comment</p>
                            </div>
                        </div>
                        <div
                            class="flex justify-center flex-col"
                            v-if="post.comments"
                        >
                            <comment-box
                                :comments="post.comments"
                                :added_comments="added_comments"
                                :post_id="post.id"
                            ></comment-box>
                        </div>
                        <transition name="scale">
                            <comment-form
                                @addComment="addComment"
                            ></comment-form>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.scale-enter-active,
.scale-leave-active {
    transition: all 0.5s ease;
}

.scale-enter-from,
.scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}
</style>
<script>
import Sidebar from "../../components/Sidebar";
import Nav from "../../components/Nav";
import { mapActions, mapMutations } from "vuex";
import Reaction from "../../components/Reaction";
import moment from "moment";
import CommentBox from "../../components/CommentBox";
import CommentForm from "../../components/CommentForm";


export default {
    name: "ShowPost",
    components: {
        Sidebar,
        Nav,
        Reaction,
        CommentBox,
        CommentForm
    },
    data: () => {
        return {
            post: {},
            user_react_type: null,
            showCommentForm: false,
            added_comments: []
        };
    },
    computed: {
        createdMoment() {
            const created_at = moment(this.post.created_at).fromNow();
            return created_at;
        },
        profileImage() {
            const defaultImage = "/default.jpg";
            if (this.post.user) {
                const profileImage =  this.post.user.profile_image;
                return this.post.user.profile_image
                    ? profileImage
                    : defaultImage;
            }
            return defaultImage;
        },
        reacts_count() {
            return (
                +this.post.like_count +
                +this.post.love_count +
                +this.post.haha_count +
                +this.post.sad_count +
                +this.post.wow_count +
                +this.post.angry_count
            );
        }
    },
    methods: {
        ...mapActions({
            fetchPost: "newsfeed/getPost",
            sendReaction: "newsfeed/sendReaction",
            removeReaction: "newsfeed/removeReaction",
            clearStorage: "auth/CLEAR_STORAGE",
            postComment: "newsfeed/postComment"
        }),
        addComment(comment) {
            if (!comment.trim()) return;
            this.postComment({ post_id: this.post.id, comment })
                .then(res => {
                    // this.added_comments.push(res.data);
                    // this.post.comments_count++;
                })
                .catch(err => {
                    if (err.status === 401) {
                        this.clearStorage();
                        this.$toast.warning("Please login your account");
                        return this.$router.push({ name: "login" });
                    }
                    if (err.data.message)
                        this.$toast.warning(error.data.message);
                });
        },
        reactPost(type) {
            this.sendReaction({ post_id: this.post.id, type })
                .then(res => {
                    this.addReactCount(res.data.type);
                    this.user_react_type = res.data.type;
                })
                .catch(err => {
                    if (err.status === 401) {
                        this.clearStorage();
                        this.$toast.warning("Please login your account");
                        return this.$router.push({ name: "login" });
                    }
                    if (err.data.type) this.$toast.warning(error.data.type[0]);
                });
        },
        removeReact() {
            if (!this.user_react_type) return;
            this.removeReaction(this.post.id)
                .then(res => {
                    this.removeReactCount(this.user_react_type);
                    this.user_react_type = null;
                })
                .catch(err => {
                    if (err.status === 401) {
                        this.clearStorage();
                        this.$toast.warning("Please login your account");
                        return this.$router.push({ name: "login" });
                    }
                    if (err.data.message) this.$toast.warning(err.data.message);
                });
        },
        addReactCount(type) {
            this.removeReactCount(this.user_react_type);
            if (type === "like") {
                this.post.like_count++;
            } else if (type === "love") {
                this.post.love_count++;
            } else if (type === "haha") {
                this.post.haha_count++;
            } else if (type === "sad") {
                this.post.sad_count++;
            } else if (type === "wow") {
                this.post.wow_count++;
            } else if (type === "angry") {
                this.post.angry_count++;
            }
        },
        removeReactCount(type) {
            if (type === "like") {
                this.post.like_count--;
            } else if (type === "love") {
                this.post.love_count--;
            } else if (type === "haha") {
                this.post.haha_count--;
            } else if (type === "sad") {
                this.post.sad_count--;
            } else if (type === "wow") {
                this.post.wow_count--;
            } else if (type === "angry") {
                this.post.angry_count--;
            }
        },
        getPost(post_id) {
            this.fetchPost(post_id)
                .then(res => {
                    this.post = res.data;
                    this.user_react_type = res.data.user_react_type;
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
        },
        setChannel(post_id) {
            Echo.channel("comment.post." + post_id).listen(
                "CommentEvent",
                e => {
                    this.added_comments.push(e.comment);
                    this.post.comments_count++;
                }
            );
        }
    },
    created() {
        const post_id = this.$route.params.post_id;

        this.setChannel(post_id);
        this.getPost(post_id);
    }
};
</script>
