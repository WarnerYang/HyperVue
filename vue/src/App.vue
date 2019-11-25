<template>
  <div id="app">
    <el-collapse-transition>
      <notFound v-if="isNotFound"></notFound>
      <router-view></router-view>
    </el-collapse-transition>
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
  components: {notFound}
};
</script>