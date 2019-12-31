<template>
  <div>
    <div v-show="addShow" class="m-b-20">
      <router-link class="btn-link-large add-btn m-l-10" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加岗位
      </router-link>
    </div>
    <div class="pos-rel">
      <btnGroup :selectedData="multipleSelection" :type="'posts'" :isLastData="isLastData"></btnGroup>
    </div>
    <el-table :data="tableData" @selection-change="selectItem">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column label="岗位名称" prop="name"></el-table-column>
      <el-table-column label="备注" prop="remark"></el-table-column>
      <listStatus></listStatus>
      <listActions
        :toRouter="'postsEdit'"
        :deleteUrl="'admin/posts/'"
        :isLastData="isLastData"
        :type="'posts'"
      ></listActions>
    </el-table>
  </div>
</template>

<script>
import btnGroup from "../common/btnGroup";
import listStatus from "../common/listStatus";
import listActions from "../common/listActions";
import http from "../../assets/js/http";

export default {
  data() {
    return {
      tableData: [],
      multipleSelection: [],
      isLastData: false
    };
  },
  methods: {
    selectItem(val) {
      this.multipleSelection = val;
      this.isLastData =
        this.multipleSelection.length === this.tableData.length ? true : false;
    },
    getPosts() {
      this.apiGet("admin/posts").then(res => {
        this.handelResponse(res, data => {
          this.tableData = data;
        });
      });
    }
  },
  created() {
    this.getPosts();
  },
  computed: {
    addShow() {
      return _g.getHasRule("admin-posts-store");
    }
  },
  watch: {
    tableData(val) {
      this.isLastData = val.length === 1 ? true : false;
    }
  },
  components: {
    btnGroup,
    listStatus,
    listActions
  },
  mixins: [http]
};
</script>