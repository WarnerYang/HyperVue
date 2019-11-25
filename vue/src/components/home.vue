<template>
  <el-container class="home-container">
    <el-aside class="home-left">
      <el-col v-if="!isCollapse" class="sys-title">
        <template v-if="logo_type == '1'">
          <img :src="img" class="logo" />
        </template>
        <template v-else>
          <h3>{{title}}</h3>
        </template>
      </el-col>
      <leftMenu
        :menuData="menuData"
        :activeMenu="activeMenu"
        :isCollapse="isCollapse"
        :ref="leftMenu"
      ></leftMenu>
    </el-aside>

    <el-container class="home-right">
      <el-header class="home-header">
        <el-col :span="1" class="toggle-menu">
          <i
            :class="{'el-icon-s-unfold':isCollapse, 'el-icon-s-fold':!isCollapse}"
            class="header-icon"
            @click="toggleMenu"
          ></i>
        </el-col>
        <el-col :span="18" class="crumb"></el-col>
        <el-col :span="5" class="user">
          <el-dropdown trigger="click" @command="handleMenu">
            <span class="el-dropdown-link">
              {{username}}
              <i class="el-icon-arrow-down" aria-hidden="true"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item command="changePwd">修改密码</el-dropdown-item>
              <el-dropdown-item command="logout">退出</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-col>
      </el-header>

      <el-main class="home-main">
        <section>
          <el-col :span="24" class="bg-wh content-container">
            <transition name="fade" mode="out-in" appear>
              <router-view v-loading="showLoading"></router-view>
            </transition>
          </el-col>
        </section>
      </el-main>

      <changePwd ref="changePwd"></changePwd>
    </el-container>
  </el-container>
</template>

<script>

import leftMenu from "./common/leftMenu";
import changePwd from "./users/changePwd";
import http from "../assets/js/http";

export default {
  data() {
    return {
      username: "",
      menuData: [],
      img: "",
      title: "",
      logo_type: null,
      isCollapse: document.body.clientWidth < 768 ? true : false
    };
  },
  methods: {
    logout() {
      this.$confirm("确认退出吗?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          _g.openGlobalLoading();
          let data = {
            authkey: Lockr.get("authKey"),
            sessionId: Lockr.get("sessionId")
          };
          this.apiPost("admin/base/logout", data).then(res => {
            _g.closeGlobalLoading();
            this.handelResponse(res, data => {
              Lockr.rm("menus");
              Lockr.rm("authKey");
              Lockr.rm("rememberKey");
              Lockr.rm("authList");
              Lockr.rm("userInfo");
              Lockr.rm("sessionId");
              Cookies.remove("rememberPwd");
              _g.toastMsg("success", "退出成功");
              setTimeout(() => {
                router.replace("/");
              }, 500);
            });
          });
        })
        .catch(() => {});
    },
    handleMenu(val) {
      switch (val) {
        case "logout":
          this.logout();
          break;
        case "changePwd":
          this.changePwd();
          break;
      }
    },
    changePwd() {
      this.$refs.changePwd.open();
    },
    getTitleAndLogo() {
      this.apiPost("admin/base/getConfigs").then(res => {
        this.handelResponse(res, data => {
          document.title = data.SYSTEM_NAME;
          this.logo_type = data.LOGO_TYPE;
          this.title = data.SYSTEM_NAME;
          this.img = data.SYSTEM_LOGO;
        });
      });
    },
    getUsername() {
      this.username = Lockr.get("userInfo").username;
    },
    toggleMenu() {
      this.isCollapse = this.isCollapse ? false : true;
    },
    refresh() {
      _g.shallowRefresh(this.$route.name, this.$route.query);
    }
  },
  created() {
    this.getTitleAndLogo();
    let authKey = Lockr.get("authKey");
    let sessionId = Lockr.get("sessionId");
    if (!authKey || !sessionId) {
      _g.toastMsg("warning", "您尚未登录");
      setTimeout(() => {
        router.replace("/");
      }, 500);
      return;
    }
    this.getUsername();
    this.activeMenu = this.$route.meta.activeMenu;
    this.menuData = Lockr.get("menus");
  },
  computed: {},
  components: {
    leftMenu,
    changePwd
  },
  watch: {
    $route(to, from) {
      this.activeMenu = to.meta.activeMenu;
    }
  },
  mixins: [http]
};
</script>
