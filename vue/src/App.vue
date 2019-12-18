<template>
  <div id="app">
    <notFound v-if="isNotFound"></notFound>
    <router-view></router-view>
  </div>
</template>

<script>
import notFound from "./components/base/404";
export default {
  name: "app",
  computed: {
    isNotFound() {
      return !this.$route.matched || this.$route.matched.length === 0;
    }
  },
  components: { notFound }
};
</script>