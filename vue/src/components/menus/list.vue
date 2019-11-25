<template>
  <div>
    <div v-show="addShow" class="m-b-20">
      <router-link class="btn-link-large add-btn m-l-10" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加菜单
      </router-link>
    </div>
    <el-table :data="tableData" @selection-change="selectItem">
      <el-table-column type="selection" width="50"></el-table-column>
      <!-- <el-table-column prop="p_title" label="父级菜单" width="150"></el-table-column> -->
      <el-table-column prop="title" label="标题"></el-table-column>
      <el-table-column prop="url" label="页面路径" width="300"></el-table-column>
      <el-table-column prop="menu_type" label="类型">
        <template scope="scope">{{scope.row.menu_type | menuType}}</template>
      </el-table-column>
      <listStatus></listStatus>
      <listActions
        :toRouter="'menusEdit'"
        :deleteUrl="'admin/menus/'"
        :isLastData="isLastData"
        :type="'menus'"
      ></listActions>
    </el-table>
    <div class="pos-rel p-t-20">
      <btnGroup :selectedData="multipleSelection" :type="'menus'" :isLastData="isLastData"></btnGroup>
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
    }
  },
  created() {
    this.apiGet("admin/menus").then(res => {
      this.handelResponse(res, data => {
        this.tableData = data;
      });
    });
  },
  computed: {
    addShow() {
      return _g.getHasRule("admin-menus-store");
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