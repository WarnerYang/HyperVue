<template>
  <el-row>
    <el-col :span="24">
      <el-menu
        :default-active="activeMenu"
        background-color="#303133"
        text-color="#c0ccda"
        class="left-menu"
        :collapse="isCollapse"
      >
        <template v-for="(firstMenu, key) in menuData">
          <el-submenu v-if="firstMenu.child" :index="key+filling" :key="key">
            <template slot="title">
              <i :class="firstMenu.icon || 'el-icon-share'"></i>
              <span slot="title">{{firstMenu.title}}</span>
            </template>
            <el-menu-item-group>
              <template v-for="(item, key2) in firstMenu.child">
                <el-menu-item
                  :index="item.url"
                  :key="key2"
                  @click="routerChange(item)"
                >{{item.title}}</el-menu-item>
              </template>
            </el-menu-item-group>
          </el-submenu>
          <el-menu-item v-else :index="firstMenu.url" :key="key" @click="routerChange(firstMenu)">
            <i :class="firstMenu.icon || 'el-icon-share'"></i>
            <span slot="title">{{firstMenu.title}}</span>
          </el-menu-item>
        </template>
      </el-menu>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: ["menuData", "activeMenu", "isCollapse"],
  data() {
    return {
      filling: "0000"
    };
  },
  methods: {
    routerChange(item) {
      // 与当前页面路由相等则刷新页面
      if (item.url != this.$route.path) {
        // 外链新窗口打开
        if (item.menu_type == 2) {
          window.open(item.url, "_blank");
        } else {
          router.push(item.url);
        }
      } else {
        _g.shallowRefresh(this.$route.name);
      }
    }
  }
};
</script>