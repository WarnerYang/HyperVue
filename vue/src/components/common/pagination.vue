<template>
  <el-pagination
    @current-change="handleCurrentChange"
    @size-change="handleSizeChange"
    :page-sizes="[10, 15, 20, 50, 100]"
    :page-size="limit"
    :current-page="currentPage"
    :total="total"
    background
    layout="total, sizes, prev, pager, next"
  ></el-pagination>
</template>

<script>
export default {
  props: ["total"],
  data() {
    return {
      currentPage: 1,
      limit: 10
    };
  },
  created() {
    this.getCurrentPageAndLimit();
  },
  methods: {
    handleCurrentChange(page) {
      this.currentPage = page;
      this.pushQuery();
    },
    handleSizeChange(limit) {
      this.currentPage = 1;
      this.limit = limit;
      this.pushQuery();
    },
    getCurrentPageAndLimit() {
      const query = this.$route.query;
      this.currentPage = query.page ? parseInt(query.page) : this.currentPage;
      this.limit = query.limit ? parseInt(query.limit) : this.limit;
    },
    pushQuery() {
      const query = {
        ...this.$route.query,
        ...{ limit: this.limit, page: this.currentPage }
      };
      router.push({
        path: this.$route.path,
        query: query
      });
    }
  },
  watch: {
    $route(to, from) {
      this.getCurrentPageAndLimit();
    }
  }
};
</script>
