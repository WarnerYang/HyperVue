<template>
  <div class="m-l-50 m-t-30 w-500">
    <el-form ref="form" :model="form" :rules="rules" label-width="130px">
      <el-form-item label="用户名" prop="username">
        <el-input v-model.trim="form.username" class="h-40 w-200" :maxlength="12"></el-input>
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model.trim="form.password" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="真实姓名" prop="realname">
        <el-input v-model.trim="form.realname" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="部门" prop="structure_id">
        <el-select v-model="form.structure_id" placeholder="请选择部门" class="w-200">
          <el-option
            v-for="item in orgsOptions"
            :label="item.title"
            :value="item.id"
            :key="item.id"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="岗位" prop="post_id">
        <el-select v-model="form.post_id" placeholder="请选择岗位" class="w-200">
          <el-option v-for="item in postOptions" :label="item.name" :value="item.id" :key="item.id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="备注">
        <el-input v-model.trim="form.remark" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="用户组">
        <el-checkbox-group v-model="selectedGroups">
          <el-checkbox
            v-for="item in groupOptions"
            :label="item.else"
            class="form-checkbox"
            :key="item.id"
          ></el-checkbox>
        </el-checkbox-group>
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
        username: "",
        password: "",
        realname: "",
        structure_id: null,
        post_id: null,
        remark: "",
        groups: []
      },
      orgsOptions: [],
      postOptions: [],
      groupOptions: [],
      selectedGroups: [],
      selectedIds: [],
      rules: {
        username: [{ required: true, message: "请输入用户名" }],
        password: [{ required: true, message: "请输入用户密码" }],
        realname: [{ required: true, message: "请输入真实姓名" }],
        structure_id: [{ required: true, message: "请选择部门" }]
      }
    };
  },
  methods: {
    selectCheckbox() {
      let temp = false;
      _(this.groupOptions).forEach(res => {
        if (this.selectedGroups.toString().indexOf(res.title) > -1) {
          this.selectedIds.push(res.id);
        }
      });
      if (this.selectedIds.length) {
        this.form.groups = _.cloneDeep(this.selectedIds);
        temp = true;
      }
      this.selectedIds = [];
      return temp;
    },
    add(form) {
      if (!this.selectCheckbox()) {
        _g.toastMsg("warning", "请选择用户组");
        return;
      }
      this.$refs.form.validate(pass => {
        if (pass) {
          this.isLoading = !this.isLoading;
          this.apiPost("admin/users", this.form).then(res => {
            this.handelResponse(
              res,
              data => {
                _g.toastMsg("success", "添加成功");
                _g.clearVuex("setUsers");
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
    getAllGroups() {
      this.apiGet("admin/groups").then(res => {
        this.handelResponse(res, data => {
          this.groupOptions = data;
        });
      });
    },
    getAllOrgs() {
      this.apiGet("admin/structures").then(res => {
        this.handelResponse(res, data => {
          this.orgsOptions = data;
        });
      });
    },
    getAllposts() {
      this.apiGet("admin/posts").then(res => {
        this.handelResponse(res, data => {
          this.postOptions = data;
        });
      });
    }
  },
  created() {
    this.getAllGroups();
    this.getAllOrgs();
    this.getAllposts();
  },
  mixins: [http, fomrMixin]
};
</script>