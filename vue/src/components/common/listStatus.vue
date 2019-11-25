<template>
  <el-table-column :label="label||'状态'" :prop="prop||'status'" :width="width">
    <template scope="scope">
      <el-tag :type="scope.row.status | tagType">{{ scope.row.status | status}}</el-tag>
    </template>
  </el-table-column>
</template>

<script>
export default {
  props: ["label", "prop", "width"]
};
</script>