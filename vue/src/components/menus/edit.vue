<template>
  <div class="m-l-50 m-t-30 w-500">
    <el-form ref="form" :model="form" :rules="rules" label-width="110px">
      <el-form-item label="标题" prop="title">
        <el-input v-model.trim="form.title" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="绑定权限" prop="rule_name">
        <el-input v-model.trim="form.rule_name" class="h-40 fl w-200" :disabled="true"></el-input>
        <el-button class="fl m-l-30" @click="openRule()">查找</el-button>
      </el-form-item>
      <el-form-item label="菜单类型" prop="menu_type">
        <el-radio-group v-model="form.menu_type">
          <el-radio label="1">{{1 | menuType}}</el-radio>
          <el-radio label="2">{{2 | menuType}}</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="父级菜单" prop="pid">
        <el-select v-model="form.pid" placeholder="请选择活动区域" class="w-200">
          <el-option
            v-for="item in options"
            :label="item.title"
            :value="item.id"
            :key="item.id"
            :disabled="item.id == form.id"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="页面路径" prop="url">
        <el-input v-model.trim="form.url" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="图标" prop="icon">
        <el-input v-model.trim="form.icon" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="排序" prop="sort">
        <el-input v-model="form.sort" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="edit('form')" :loading="isLoading">提交</el-button>
        <el-button @click="goback()">返回</el-button>
      </el-form-item>
    </el-form>
    <ruleList ref="ruleList"></ruleList>
  </div>
</template>

<script>
import ruleList from "./rule";
import http from "../../assets/js/http";
import fomrMixin from "../../assets/js/form_com";

export default {
  data() {
    return {
      loading: false,
      id: null,
      form: {
        title: "",
        rule_name: "",
        rule_id: null,
        pid: null,
        menu_type: "",
        url: "",
        sort: "",
        icon: ""
      },
      options: [{ id: 0, title: "无" }],
      rules: {
        title: [{ required: true, message: "请输入菜单标题" }],
        rule_name: [{ required: true, message: "请绑定权限" }],
        menu_type: [{ required: true, message: "请选择菜单类型" }],
        pid: [{ required: true, type: "number", message: "请选择父级菜单" }]
      }
    };
  },
  methods: {
    edit(form) {
      this.$refs.form.validate(pass => {
        if (pass) {
          this.isLoading = !this.isLoading;
          this.apiPut("admin/menus/", this.id, this.form).then(res => {
            this.handelResponse(
              res,
              data => {
                _g.toastMsg("success", "编辑成功");
                setTimeout(() => {
                  this.goback();
                }, 500);
              },
              () => {
                this.isLoading = !this.isLoading;
              }
            );
          });
        }
      });
    },
    openRule() {
      this.$refs.ruleList.open(this.form);
    },
    goback() {
      router.go(-1);
    },
    getMenus() {
      this.apiGet("admin/menus").then(res => {
        this.handelResponse(res, data => {
          let array = [];
          _(data).forEach(res => {
            array.push(res);
          });
          this.options = this.options.concat(array);
        });
      });
    }
  },
  created() {
    this.getMenus();
    this.id = this.$route.params.id;
    this.apiGet("admin/menus/" + this.id).then(res => {
      this.handelResponse(res, data => {
        data.menu_type = data.menu_type.toString();
        this.form = data;
      });
    });
  },
  components: {
    ruleList
  },
  mixins: [http, fomrMixin]
};
</script>