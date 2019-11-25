<template>
  <div>
    <el-button v-show="enableShow" :loading="enableLoading" size="small" @click="changeAttrInBtnGroup(1)">启用</el-button>
    <el-button v-show="enableShow" :loading="disableLoading" size="small" @click="changeAttrInBtnGroup(0)">禁用</el-button>
    <el-button v-show="deletesShow" :loading="deleteLoading" size="small" @click="deleteDatasInBtnGroup()">删除</el-button>
  </div>
</template>

<script>
import http from "../../assets/js/http";

export default {
  props: ["selectedData", "type", "isLastData"],
  data() {
    return {
      enableLoading: false,
      disableLoading: false,
      deleteLoading: false
    };
  },
  methods: {
    getUrl() {
      return "admin/" + this.type;
    },
    getSelectedIds() {
      let array = [];
      _(this.selectedData).forEach(res => {
        array.push(res.id);
      });
      return array;
    },
    changeAttrInBtnGroup(cate) {
      if (!this.selectedData.length) {
        _g.toastMsg("warning", "请勾选数据");
        return;
      }
      let word = "";
      if (cate == 1) {
        word = "启用";
        this.enableLoading = !this.enableLoading;
      } else {
        this.disableLoading = !this.disableLoading;
        word = "禁用";
      }
      let url = this.getUrl() + "/enables";
      let data = {
        ids: this.getSelectedIds(),
        status: cate
      };
      this.apiPost(url, data).then(res => {
        this.handelResponse(
          res,
          data => {
            _g.toastMsg("success", word + "成功");
            setTimeout(() => {
              _g.shallowRefresh(this.$route.name, this.$route.query);
            }, 500);
          },
          () => {
            if (cate == 1) {
              this.enableLoading = !this.enableLoading;
            } else {
              this.disableLoading = !this.disableLoading;
            }
          }
        );
      });
    },
    deleteDatasInBtnGroup() {
      if (!this.selectedData.length) {
        _g.toastMsg("warning", "请勾选数据");
        return;
      }
      this.deleteLoading = !this.deleteLoading;
      let url = this.getUrl() + "/deletes";
      let data = {
        ids: this.getSelectedIds()
      };
      this.apiPost(url, data).then(res => {
        this.handelResponse(
          res,
          data => {
            let query = this.$route.query;
            if (this.isLastData && query.page) {
              query.page -= 1;
              if (query.page === 0) delete query.page;
            }
            _g.toastMsg("success", "删除成功");
            _g.shallowRefresh(this.$route.name, this.$route.query);
          },
          () => {
            this.deleteLoading = !this.deleteLoading;
          }
        );
      });
    }
  },
  computed: {
    enableShow() {
      return _g.getHasRule("admin-" + this.type + "-enables");
    },
    deletesShow() {
      return _g.getHasRule("admin-" + this.type + "-deletes");
    }
  },
  mixins: [http]
};
</script>