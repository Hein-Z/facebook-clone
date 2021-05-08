<template>
    <div
        class="like-btn w-full justify-center flex"
        @mouseover="animiation"
        @mouseleave="removeAnimiation"
    >
        <!-- like btn -->
        <div
            class="flex w-full justify-center py-1 rounded-lg hover:bg-blue-100"
            v-if="!user_react_type"
            @click="likePost"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                class="fill-current w-7 h-7 like-hover"
            >
                <path
                    d="M20.8 15.6c.4-.5.6-1.1.6-1.7 0-.6-.3-1.1-.5-1.4.3-.7.4-1.7-.5-2.6-.7-.6-1.8-.9-3.4-.8-1.1.1-2 .3-2.1.3-.2 0-.4.1-.7.1 0-.3 0-.9.5-2.4.6-1.8.6-3.1-.1-4.1-.7-1-1.8-1-2.1-1-.3 0-.6.1-.8.4-.5.5-.4 1.5-.4 2-.4 1.5-2 5.1-3.3 6.1l-.1.1c-.4.4-.6.8-.8 1.2-.2-.1-.5-.2-.8-.2H3.7c-1 0-1.7.8-1.7 1.7v6.8c0 1 .8 1.7 1.7 1.7h2.5c.4 0 .7-.1 1-.3l1 .1c.2 0 2.8.4 5.6.3.5 0 1 .1 1.4.1.7 0 1.4-.1 1.9-.2 1.3-.3 2.2-.8 2.6-1.6.3-.6.3-1.2.3-1.6.8-.8 1-1.6.9-2.2.1-.3 0-.6-.1-.8zM3.7 20.7c-.3 0-.6-.3-.6-.6v-6.8c0-.3.3-.6.6-.6h2.5c.3 0 .6.3.6.6v6.8c0 .3-.3.6-.6.6H3.7zm16.1-5.6c-.2.2-.2.5-.1.7 0 0 .2.3.2.7 0 .5-.2 1-.8 1.4-.2.2-.3.4-.2.6 0 0 .2.6-.1 1.1-.3.5-.9.9-1.8 1.1-.8.2-1.8.2-3 .1h-.1c-2.7.1-5.4-.3-5.4-.3H8v-7.2c0-.2 0-.4-.1-.5.1-.3.3-.9.8-1.4 1.9-1.5 3.7-6.5 3.8-6.7v-.3c-.1-.5 0-1 .1-1.2.2 0 .8.1 1.2.6.4.6.4 1.6-.1 3-.7 2.1-.7 3.2-.2 3.7.3.2.6.3.9.2.3-.1.5-.1.7-.1h.1c1.3-.3 3.6-.5 4.4.3.7.6.2 1.4.1 1.5-.2.2-.1.5.1.7 0 0 .4.4.5 1 0 .3-.2.6-.5 1z"
                />
            </svg>
            <p class="ml-2 text-lg font-bold">
                Like <span class="count">{{ count }}</span>
            </p>
        </div>
        <user-react-type
            :user_react_type="user_react_type"
            :count="count"
            v-if="user_react_type"
            @mouseover.native="animiation"
            @mouseleave.native="removeAnimiation"
            @remove="remove"
        ></user-react-type>
        <div class="reaction-box">
            <div class="reaction-icon" @click="react('like')">
                <img
                    src="reactions/like.svg"
                    alt=""
                    class="icon"
                    :class="{ show: showings[0] }"
                />
                <label class="icon-text">Like</label>
            </div>
            <div class="reaction-icon" @click="react('love')">
                <img
                    src="reactions/love.svg"
                    alt=""
                    class="icon"
                    :class="{ show: showings[1] }"
                />

                <label class="icon-text">Love</label>
            </div>
            <div class="reaction-icon" @click="react('haha')">
                <img
                    src="reactions/haha.svg"
                    alt=""
                    class="icon"
                    :class="{ show: showings[2] }"
                />

                <label class="icon-text">Haha</label>
            </div>
            <div class="reaction-icon" @click="react('wow')">
                <img
                    src="reactions/wow.svg"
                    alt=""
                    class="icon"
                    :class="{ show: showings[3] }"
                />

                <label class="icon-text">Wow</label>
            </div>
            <div class="reaction-icon" @click="react('sad')">
                <img
                    src="reactions/sad.svg"
                    alt=""
                    class="icon"
                    :class="{ show: showings[4] }"
                />

                <label class="icon-text">Sad</label>
            </div>
            <div class="reaction-icon" @click="react('angry')">
                <img
                    src="reactions/angry.svg"
                    alt=""
                    class="icon"
                    :class="{ show: showings[5] }"
                />

                <label class="icon-text">Angry</label>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.like-btn {
    z-index: 1000;
    .count {
        display: none;
    }
    position: relative;
    cursor: pointer;
    &::before {
        content: ".";
        opacity: 0;
        display: block;
        width: 260px;
        height: 10px;
        position: absolute;
        top: -10px;
        left: 0;
    }

    .reaction-box {
        position: absolute;
        width: 260px;
        height: 45px;
        background: #f0c674;
        border-radius: 28px;
        left: 38px;
        bottom: 34px;
        display: none;

        .reaction-icon {
            display: inline-block;
            .icon-text {
                padding: 1px 5px 1px 5px;
                position: absolute;
                top: -15px;
                margin: 0 6px;
                border-radius: 10px;
                font-size: 11px;
                color: #fff;
                background: #333;
                opacity: 0;
            }

            .icon {
                width: 32px;
                height: 32px;

                margin: 7px -1px 0 8px;
                text-align: center;
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                opacity: 0;
                transform: translate(0, 100px) scale(0);
            }
        }
    }

    &:hover {
        .reaction-box {
            display: block;
            .reaction-icon {
                .icon {
                    &.show {
                        opacity: 1;
                        transform: translate(0, 0) scale(1);
                    }
                }
                &:hover {
                    // changed here!
                    transform: scale(1.4);
                    transform-origin: bottom;
                    .icon-text {
                        opacity: 1;
                    }
                }
            }
        }
        .like-hover {
            fill: rgba(0, 102, 255, 0.904);
        }
    }
}
</style>
<script>
import UserReactType from "./UserReactType";
export default {
    props: ["user_react_type"],
    name: "reaction",
    data() {
        return {
            showings: [],
            count: 0
        };
    },
    components: {
        UserReactType
    },
    methods: {
        animiation() {
            this.showings.forEach(function(curr, index) {
                setTimeout(_ => {
                    this.showings[index] = true;
                    this.count++;
                }, index * 100);
            }, this);
        },
        removeAnimiation() {
            this.showings = [false, false, false, false, false, false];
        },
        react(value) {
            if (this.user_react_type === value) return;
            this.$emit("onReact", value);
        },
        remove() {
            if (this.user_react_type) {
                this.$emit("removeReact");
            }
        },
        likePost() {
            if (!this.user_react_type) {
                this.$emit("onReact", "like");
            }
        }
    },
    created() {
        this.showings = [false, false, false, false, false, false];
    }
};
</script>
