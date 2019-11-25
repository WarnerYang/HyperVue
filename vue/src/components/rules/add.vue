<template>
  <div class="m-l-50 m-t-30 w-500">
    <el-form ref="form" :model="form" :rules="rules" label-width="110px">
      <el-form-item label="标题" prop="title">
        <el-input v-model.trim="form.title" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="权限标识" prop="name">
        <el-input v-model.trim="form.name" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="权限类型" prop="level">
        <el-radio-group v-model="form.level">
          <el-radio label="1">{{1 | ruleLevel}}</el-radio>
          <el-radio label="2">{{2 | ruleLevel}}</el-radio>
          <el-radio label="3">{{3 | ruleLevel}}</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="父级权限" prop="pid">
        <el-select v-model="form.pid" placeholder="请选择父级权限" class="w-200">
          <el-option v-for="item in options" :label="item.title" :value="item.id" :key="item.id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="add('form')" :loading="isLoading">提交</el-button>
        <el-button @click="goback()">返回</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import http from "../../assets/js/http";
import fomrMixin from "../../assets/js/form_com";

export default {
  data() {
    return {
      isLoading: false,
      form: {
        title: "",
        name: "",
        pid: 0,
        level: "1"
      },
      options: [{ id: 0, title: "无" }],
      rules: {
        title: [{ required: true, message: "请输入标题" }],
        name: [{ required: true, message: "请输入权限标识" }],
        level: [{ required: true, message: "请选择权限类型" }],
        pid: [{ type: "number", required: true, message: "请选择父级权限" }]
      }
    };
  },
  methods: {
    add(form) {
      this.$refs[form].validate(valid => {
        if (valid) {
          this.isLoading = !this.isLoading;
          this.apiPost("admin/rules", this.form).then(res => {
            this.handelResponse(
              res,
              data => {
                _g.toastMsg("success", "添加成功");
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
    getRules() {
      this.apiGet("admin/rules").then(res => {
        this.handelResponse(res, data => {
          _(data).forEach(ret => {
            if (ret.level != 3) {
              this.options.push(ret);
            }
          });
        });
      });
    }
  },
  created() {
    this.getRules();
  },
  mixins: [http, fomrMixin]
};
</script>