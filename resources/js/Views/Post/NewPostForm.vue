<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar />

            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <div class="flex justify-center">

                    </div>
                    <div
                        class="bg-white rounded shadow w-2/3 p-4 cursor-pointer"
                        @click="$emit('toNewPostForm')"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="w-8">
                                    <img
                                        :src="profileImage"
                                        alt="profile image for user"
                                        class="w-8 h-8 object-cover rounded-full"
                                    />
                                </div>
                            </div>
                            <div class="flex-1 mx-4">
                                <label class="text-lg font-semibold mb-3">
                                    What is on your mind</label
                                >
                                <textarea
                                    type="text"
                                    name="status"
                                    class="w-full pl-4 pt-5  bg-gray-200 resize-none h-48 focus:outline-none focus:shadow-outline text-sm"
                                    placeholder="Aa..."
                                    rows="10"
                                    v-model="status"
                                />
                            </div>
                            <div>
                                <button
                                    class="flex justify-center items-center rounded-full w-10 h-10 bg-gray-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        class="bi bi-arrow-up-square-fill"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Nav from "../../components/Nav";
import Sidebar from "../../components/Sidebar";
import { mapGetters } from "vuex";


export default {
    name: "new-post-form",
    data() {
        return {
            images: [],
            status: ""
        };
    },
    components: {
        Sidebar,
        Nav,
        VueUploadMultipleImage
    },
    computed: {
        ...mapGetters({
            profile_image: "auth/getProfileImage"
        }),
        profileImage() {
            const profileImage = this.profile_image;
            const defaultImage = process.env.MIX_APP_URL + "/default.jpg";

            return this.profile_image ? profileImage : defaultImage;
        }
    },

};
</script>
