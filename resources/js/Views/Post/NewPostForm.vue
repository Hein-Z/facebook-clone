<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar />

            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <div class="flex justify-center">
                        <el-upload
                            list-type="picture-card"
                            action="api/posts/el-upload"
                            :on-preview="handlePictureCardPreview"
                            :auto-upload="true"
                            :on-remove="handleRemove"
                            :before-upload="beforeUpload"
                        >
                            <i class="el-icon-plus"></i>
                        </el-upload>
                        <el-dialog :visible.sync="dialogVisible">
                            <img :src="dialogImageUrl" width="100%" />
                        </el-dialog>
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
                                    class="flex justify-center items-center rounded-full w-10 h-10 bg-gray-200 btn"
                                    :class="{ 'opacity-50': isCreatingPost }"
                                    @click="createPost"
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
            dialogImageUrl: "",
            dialogVisible: false,
            imageList: [],
            isCreatingPost: false,
            status: ""
        };
    },
    components: {
        Sidebar,
        Nav
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
    methods: {
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },
        handleRemove(file, fileList) {
            this.imageList = [];
            fileList.forEach(f => {
                this.imageList.push(f.raw);
            });
        },
        updateImageList(file) {
            this.imageList.push(file);
        },
        checkType(file) {
            const acceptType = [
                "image/jpeg",
                "image/jpg",
                "image/gif",
                "image/png"
            ];
            return acceptType.includes(file.type);
        },
        checkSize(file) {
            return file.size / 1024 / 1024 < 2;
        },
        beforeUpload(file) {
            const isImg = this.checkType(file);
            const isLt2M = this.checkSize(file);
            if (!isImg) this.$toast.error("Picture must be Image!");

            if (!isLt2M) this.$$toast.error("Picture size can not exceed 2MB!");

            if (isImg && isLt2M) this.updateImageList(file);

            return isImg && isLt2M;
        },
        createPost() {
            if (!this.status.trim()) {
                this.$toast.warning("status is required");
                return;
            }
            this.isCreatingPost = true;
            const formData = new FormData();

            formData.append("status", this.status);
            $.each(this.imageList, function(key, image) {
                formData.append(`images[${key}]`, image);
            });
            axios
                .post("posts", formData, {
                    headers: { "Content-Type": "multipart/form-data" }
                })
                .then(res => {
                    this.$toast.success("successfully created post");
                    this.$router.push({ name: "home" });
                })
                .catch(err => {
                    this.$toast.warning(
                        "status is required or image must be type of image"
                    );
                });
        }
    }
};
</script>
