<template >
  <el-table-column v-if="showColumm" :label="label||'操作'" :width="width">
    <template scope="scope">
      <router-link
        v-show="editShow"
        :to="{ name: toRouter, params: { id: scope.row.id }}"
        class="p-r-10"
      >
        <el-link type="primary" icon="el-icon-edit">编辑</el-link>
      </router-link>
      <el-link
        v-show="deleteShow"
        type="danger"
        icon="el-icon-delete"
        @click="confirmDelete(scope.row)"
      >删除</el-link>
    </template>
  </el-table-column>
</template>

<script>
import http from "../../assets/js/http";
export default {
  props: ["label", "width", "toRouter", "deleteUrl", "isLastData", "type"],
  data() {
    return {
      showColumm: true
    };
  },
  methods: {
    confirmDelete(item) {
      const title = item.else || item.name || item.title || item.username || "";
      const tips = `确认删除 ${title} ?`;
      const url = this.deleteUrl;
      this.$confirm(tips, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          _g.openGlobalLoading();
          this.apiDelete(url, item.id).then(res => {
            _g.closeGlobalLoading();
            this.handelResponse(res, data => {
              let query = this.$route.query;
              if (this.isLastData && query.page) {
                query.page -= 1;
                if (query.page === 0) delete query.page;
              }
              _g.toastMsg("success", "删除成功");
              _g.shallowRefresh(this.$route.name, this.$route.query);
            });
          });
        })
        .catch(e => {
          console.error(e);
        });
    }
  },
  created() {
    this.showColumm = this.editShow || this.deleteShow ? true : false;
  },
  computed: {
    editShow() {
      return _g.getHasRule("admin-" + this.type + "-update");
    },
    deleteShow() {
      return _g.getHasRule("admin-" + this.type + "-destroy");
    }
  },
  mixins: [http]
};
</script>