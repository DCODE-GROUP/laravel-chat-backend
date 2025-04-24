<script>
import LaravelChatHeader from "./LaravelChatHeader.vue";
import LaravelChatPost from "./LaravelChatPost.vue";

export default {
  name: "LaravelChatMessages",
  components: {LaravelChatPost, LaravelChatHeader}
}
</script>

<template>
  <div class="laravel-chat__messages">
    <LaravelChatHeader></LaravelChatHeader>

    <div class="laravel-chat__messages-list">
      <!-- Messages will be displayed here -->
    </div>

    <LaravelChatPost></LaravelChatPost>

  </div>
</template>

<style scoped>

</style>