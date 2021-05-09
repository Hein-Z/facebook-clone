<template>
    <div>
        <comment v-for="comment in comments" :key="comment.id" :comment=comment></comment>
    </div>
</template>
<script>
import Comment from "../components/Comment";
export default {
    props: ["comments"],
    components: {
        Comment
    }
};
</script>
