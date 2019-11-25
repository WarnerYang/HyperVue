<template>
  <el-drawer
    title="权限列表"
    :visible.sync="drawer"
    custom-class="drawer-rtl"
    direction="rtl"
    size="50%"
  >
    <el-table :data="tableData" :row-class-name="tableRowClassName">
      <el-table-column prop="id" label="权限ID" width="100"></el-table-column>
      <el-table-column prop="title" label="标题"></el-table-column>
      <el-table-column prop="name" label="权限标识"></el-table-column>
      <el-table-column prop label="操作"  width="100">
        <template scope="scope">
          <el-button v-if="scope.row.id === selected" size="small" disabled>已绑定</el-button>
          <el-button v-else size="small" @click="selectRule(scope.row)">绑定</el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-drawer>
</template>
<script>
import http from "../../assets/js/http";

export default {
  data() {
    return {
      tableData: [],
      drawer: false,
      selected: ""
    };
  },
  methods: {
    open(form) {
      this.drawer = true;
      this.selected = form.rule_id;
    },
    close() {
      this.drawer = false;
    },
    selectRule(item) {
      this.$parent.form.rule_name = item.else;
      this.$parent.form.rule_id = item.id;
      this.close();
    },
    getRules() {
      this.apiGet("admin/rules").then(res => {
        this.handelResponse(res, data => {
          this.tableData = _(data).forEach();
        });
      });
    },
    tableRowClassName({ row, rowIndex }) {
      if (this.selected === row.id) {
        return "success-row";
      }
      return "";
    }
  },
  created() {
    let data = store.state.rules;
    if (data && data.length) {
      this.tableData = _(data).forEach();
    } else {
      this.getRules();
    }
  },
  mixins: [http]
};
</script>