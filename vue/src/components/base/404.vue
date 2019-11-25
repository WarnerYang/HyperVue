<template>
  <div class="notfound">
    <p>页面未找到 404</p>
    <el-link type="primary" class="m-r-5" @click="$router.push('/')">登录</el-link>
    <el-link type="primary" class="m-l-5" @click="$router.go(-1)">返回</el-link>
  </div>
</template>

<script>
export default {};
</script>