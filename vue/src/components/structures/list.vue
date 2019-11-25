<template>
  <div>
    <div v-show="addShow" class="m-b-20">
      <router-link class="btn-link-large add-btn m-l-10" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加部门
      </router-link>
    </div>
    <el-table :data="tableData" @selection-change="selectItem">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column label="部门结构" prop="title"></el-table-column>
      <el-table-column label="部门名称" prop="name"></el-table-column>
      <listStatus></listStatus>
      <listActions
        :toRouter="'structuresEdit'"
        :deleteUrl="'admin/structures/'"
        :isLastData="isLastData"
        :type="'structures'"
      ></listActions>
    </el-table>
    <div class="pos-rel p-t-20">
      <btnGroup :selectedData="multipleSelection" :type="'structures'" :isLastData="isLastData"></btnGroup>
    </div>
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
    getStructures() {
      this.apiGet("admin/structures").then(res => {
        this.handelResponse(res, data => {
          this.tableData = data;
        });
      });
    }
  },
  created() {
    this.getStructures();
  },
  computed: {
    addShow() {
      return _g.getHasRule("admin-structures-store");
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