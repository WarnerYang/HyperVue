<template>
  <div>
    <div v-show="addShow" class="m-b-20">
      <router-link class="btn-link-large add-btn m-l-10" to="add">
        <i class="el-icon-plus"></i>&nbsp;&nbsp;添加权限
      </router-link>
    </div>
    <div class="pos-rel">
      <btnGroup :selectedData="multipleSelection" :type="'rules'" :isLastData="isLastData"></btnGroup>
    </div>
    <el-table :data="tableData" @selection-change="selectItem">
      <el-table-column type="selection" width="50"></el-table-column>
      <!-- <el-table-column prop="p_title" label="权限结构"></el-table-column> -->
      <el-table-column prop="title" label="标题"></el-table-column>
      <el-table-column prop="name" label="标识名称"></el-table-column>
      <listStatus></listStatus>
      <listActions
        :toRouter="'rulesEdit'"
        :deleteUrl="'admin/rules/'"
        :isLastData="isLastData"
        :type="'rules'"
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
    }
  },
  created() {
    this.apiGet("admin/rules").then(res => {
      this.handelResponse(res, data => {
        this.tableData = data;
      });
    });
  },
  computed: {
    addShow() {
      return _g.getHasRule("admin-rules-store");
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