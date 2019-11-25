<template>
  <el-dialog ref="dialog" custom-class="bg-wh tx-c box-shadow-none">
    <img :src="url" />
  </el-dialog>
</template>
<script>
export default {
  props: ["url"],
  data() {
    return {};
  },
  methods: {
    open() {
      this.$refs.dialog.open();
    }
  }
};
</script>