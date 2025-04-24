<script lang="ts" setup>
export default {
  name: "LaravelChat"
}
</script>

<template>
  <div class="laravel-chat">
    <div class="laravel-chat__left-column">
      <LaravelChatLeftColumn/>
    </div>
    <div class="laravel-chat__right-column">
      <LaravelChatMessages/>
    </div>
  </div>


</template>

<style scoped>

</style>