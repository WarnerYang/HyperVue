<template>
  <el-dialog ref="dialog" custom-class="w-400 h-300" title="修改密码" :visible.sync="dialogVisible">
    <div class="ovf-auto">
      <el-form ref="form" :model="form" :rules="rules" label-width="80px">
        <el-form-item label="旧密码" prop="old_pwd">
          <el-input
            type="text"
            v-model.trim="form.old_pwd"
            :clearable="true"
          ></el-input>
        </el-form-item>
        <el-form-item label="新密码" prop="new_pwd">
          <el-input
            type="text"
            v-model.trim="form.new_pwd"
            :clearable="true"
            @keyup.enter.native="submit()"
          ></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="p-t-20">
      <el-button type="primary" class="fl m-l-20" :disabled="disable" @click="submit()">提交</el-button>
    </div>
  </el-dialog>
</template>

<script>
import http from "../../assets/js/http";

export default {
  data() {
    return {
      disable: false,
      form: {
        old_pwd: "",
        new_pwd: ""
      },
      rules: {
        old_pwd: [
          { required: true, message: "请输入旧密码", trigger: "blur" },
          { min: 6, max: 12, message: "长度在 6 到 12 个字符", trigger: "blur" }
        ],
        new_pwd: [
          { required: true, message: "请输入新密码", trigger: "blur" },
          { min: 6, max: 12, message: "长度在 6 到 12 个字符", trigger: "blur" }
        ]
      },
      dialogVisible: false
    };
  },
  methods: {
    open() {
      this.dialogVisible = true;
    },
    close() {
      this.dialogVisible = false;
    },
    submit() {
      this.$refs.form.validate(pass => {
        if (pass) {
          this.disable = !this.disable;
          this.apiPost("admin/base/changePwd", this.form).then(res => {
            this.disable = !this.disable;
            this.handelResponse(res, data => {
              _g.toastMsg("success", "修改成功");
              this.dialogVisible = !this.dialogVisible;
              Lockr.set("sessionId", data.sessionId); // 更新用户 sessionid
              Lockr.set("authKey", data.authKey); // 更新权限认证
              window.axios.defaults.headers.authKey = data.sessionId;
              window.axios.defaults.headers.sessionId = data.authKey;
            });
          });
        }
      });
    }
  },
  mixins: [http]
};
</script>