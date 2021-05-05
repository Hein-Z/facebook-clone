<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav/>

        <div class="flex overflow-y-hidden flex-1 ">
            <Sidebar/>
            <div class="overflow-x-hidden w-4/5">
                <div class="flex flex-col items-center py-4 ">
                    <NewPost/>
                    <Post/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NewPost from '../components/NewPost';
import Post from '../components/Post';
import Sidebar from "../components/Sidebar";
import Nav from "../components/Nav";

export default {
    name: "NewsFeed",

    components: {
        NewPost,
        Post,
        Sidebar,
        Nav
    }
}
</script>

<style scoped>

</style>
