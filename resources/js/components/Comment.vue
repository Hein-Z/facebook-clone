<template>
    <div
        class=" bg-white dark:bg-gray-800 text-black dark:text-gray-200 p-4 antialiased flex max-w-lg"
    >
        <img class="rounded-full h-8 w-8 mr-2 mt-1 " :src="profileImage" />
        <div>
            <div
                class="bg-gray-100 dark:bg-gray-700 rounded-3xl px-4 pt-2 pb-2.5"
            >
                <div class="font-semibold text-sm leading-relaxed">
                    {{ comment.user.name }}
                </div>
                <div class="text-normal leading-snug md:leading-normal">
                    {{ comment.comment }}
                </div>
            </div>
            <div class="text-sm ml-4 mt-0.5 text-gray-500 dark:text-gray-400">
                {{ createdMoment }}
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    props: ["comment"],
    computed: {
        profileImage() {
            const profileImage =  this.comment.user.profile_image;
            const defaultImage = "/default.jpg";

            return this.comment.user.profile_image
                ? profileImage
                : defaultImage;
        },
        createdMoment() {
            const created_at = moment(this.comment.created_at).fromNow();
            return created_at;
        }
    }
};
</script>
