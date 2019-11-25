<template>
  <div></div>
</template>
<script>
export default {
  created() {
    if (this.$route.query.name) {
      router.replace({
        name: this.$route.query.name,
        query: this.$route.query.query
      });
    } else {
      console.log("refresh fail");
    }
  }
};
</script>