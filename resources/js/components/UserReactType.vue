<template>
    <div class="w-full" @click="remove">
        <!-- liked -->
        <div
            class="flex w-full  justify-center bg-gray-300 py-1 rounded-lg"
            v-if="user_react_type === 'like'"
        >
            <img src="/reactions/like.svg" alt="" class="fill-current w-7 h-7" />
            <p class="ml-2 text-lg font-bold text-white">
                Like <span class="count">{{ count }}</span>
            </p>
        </div>

        <!-- loved -->
        <div
            class="flex w-full justify-center bg-gray-500 py-1 rounded-lg"
            v-if="user_react_type === 'love'"
        >
            <img src="/reactions/love.svg" alt="" class="fill-current w-7 h-7" />
            <p class="ml-2 text-lg font-bold text-white">
                Love <span class="count">{{ count }}</span>
            </p>
        </div>

        <!-- Haha -->
        <div
            class="flex w-full justify-center bg-gray-500 py-1 rounded-lg"
            v-if="user_react_type === 'haha'"
        >
            <img src="/reactions/haha.svg" alt="" class="fill-current w-7 h-7" />
            <p class="ml-2 text-lg font-bold text-white">
                Haha <span class="count">{{ count }}</span>
            </p>
        </div>

        <!-- sad -->
        <div
            class="flex w-full justify-center bg-gray-500 py-1 rounded-lg"
            v-if="user_react_type === 'sad'"
        >
            <img src="/reactions/sad.svg" alt="" class="fill-current w-7 h-7" />
            <p class="ml-2 text-lg font-bold text-white">
                Sad <span class="count">{{ count }}</span>
            </p>
        </div>

        <!-- wow -->
        <div
            class="flex w-full justify-center bg-gray-500 py-1 rounded-lg"
            v-if="user_react_type === 'wow'"
        >
            <img src="/reactions/wow.svg" alt="" class="fill-current w-7 h-7" />
            <p class="ml-2 text-lg font-bold text-white">
                Wow <span class="count">{{ count }}</span>
            </p>
        </div>

        <!-- angry -->
        <div
            class="flex w-full justify-center bg-gray-500 py-1 rounded-lg"
            v-if="user_react_type === 'angry'"
        >
            <img
                src="/reactions/angry.svg"
                alt=""
                class="fill-current w-7 h-7"
            />
            <p class="ml-2 text-lg font-bold text-white">
                Angry <span class="count">{{ count }}</span>
            </p>
        </div>
    </div>
</template>
<style scoped>
.count {
    display: none;
}
</style>
<script>
export default {
    props: ["user_react_type", "count"],
    methods: {
        remove() {
            if (this.user_react_type) {
                this.$emit("remove");
            }
        }
    },
    created() {}
};
</script>
