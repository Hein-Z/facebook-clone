<template>
    <div class="bg-white rounded shadow w-2/3 mt-6 relative">
        <CoolLightBox
            :items="images"
            :index="index"
            :useZoomBar="true"
            @close="index = null"
            :fullScreen="true"
        >
        </CoolLightBox>

        <div class="flex flex-col p-4">
            <div class="flex items-center">
                <router-link
                    tag="div"
                    :to="{
                        name: 'user-profile',
                        params: {
                            user_id: author_id
                        }
                    }"
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
                        :to="{
                            name: 'user-profile',
                            params: {
                                user_id: author_id
                            }
                        }"
                        class="text-sm font-bold cursor-pointer"
                        >{{ author_name }}</router-link
                    >
                    <div
                        class="text-sm text-gray-600 cursor-pointer"
                        @click="showPost"
                    >
                        {{ createdMoment }}
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <p>{{ post.status }}</p>
            </div>
        </div>

        <div class="w-full" v-if="post.images.length">
            <photo-grid
                :box-height="'600px'"
                :excess-text="'+ {{count}}'"
                v-on:clickExcess="triggerClick(3)"
                class="w-full cursor-pointer"
            >
                <img
                    v-for="image in images"
                    v-bind:src="image.src"
                    :key="image.id"
                    @click="triggerClick(images.indexOf(image))"
                />
            </photo-grid>
        </div>

        <div class="px-4 pt-2 flex justify-between text-gray-700 text-sm">
            <div class="flex">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    view="0 0 24 24"
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

        <div class="flex justify-between border-1 border-gray-400 m-4">
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
                class="flex justify-center py-2 rounded-lg text-sm text-gray-700 w-full hover:bg-gray-200"
                @click="showPost"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    view="0 0 24 24"
                    class="fill-current w-7 h-7"
                >
                    <path
                        d="M20.3 2H3.7C2 2 .6 3.4.6 5.2v10.1c0 1.7 1.4 3.1 3.1 3.1V23l6.6-4.6h9.9c1.7 0 3.1-1.4 3.1-3.1V5.2c.1-1.8-1.3-3.2-3-3.2zm1.8 13.3c0 1-.8 1.8-1.8 1.8H9.9L5 20.4V17H3.7c-1 0-1.8-.8-1.8-1.8v-10c0-1 .8-1.8 1.8-1.8h16.5c1 0 1.8.8 1.8 1.8v10.1zM6.7 6.7h10.6V8H6.7V6.7zm0 2.9h10.6v1.3H6.7V9.6zm0 2.8h10.6v1.3H6.7v-1.3z"
                    />
                </svg>
                <p class="ml-2 text-lg">Comment</p>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import Reaction from "../components/Reaction";
import { mapActions, mapMutations } from "vuex";

export default {
    name: "Post",
    props: ["post", "author_name", "author_profile_image", "author_id"],
    data() {
        return {
            user_react_type: this.post.user_react_type,
            images: [],
            index: null
        };
    },
    components: {
        Reaction
    },
    computed: {
        createdMoment() {
            const created_at = moment(this.post.created_at).fromNow();
            return created_at;
        },
        profileImage() {
            const profileImage = this.author_profile_image;
            const defaultImage = process.env.MIX_APP_URL + "/default.jpg";

            return this.author_profile_image ? profileImage : defaultImage;
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
            sendReaction: "newsfeed/sendReaction",
            removeReaction: "newsfeed/removeReaction",
            clearStorage: "auth/clearStorage"
        }),
        showPost() {
            this.$router.push({
                name: "show-post",
                params: { post_id: this.post.id }
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
                    if (err.data.type) this.$toast.warning(err.data.type[0]);
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
        triggerClick(value) {
            this.index = value;
        }
    },
    created() {
        this.post.images.forEach(image => {
            this.images.push({
                description: image.post_image_caption,
                src: image.post_image_path
            });
        });
    }
};
</script>

<style scoped></style>
