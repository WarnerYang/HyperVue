<template>
  <div>
    <div v-show="addShow" class="m-b-20 ovf-hd">
      <div class="fl" v-show="addShow">
        <router-link class="btn-link-large add-btn m-l-10" to="add">
          <i class="el-icon-plus"></i>&nbsp;&nbsp;添加用户
        </router-link>
      </div>
      <div class="fl w-200 m-l-30">
        <el-input
          size="medium"
          placeholder="请输入用户名"
          clearable
          v-model="keywords"
          @keyup.enter.native="search()"
        >
          <el-button slot="append" icon="el-icon-search" @click="search()"></el-button>
        </el-input>
      </div>
    </div>
    <div class="pos-rel">
      <btnGroup :selectedData="multipleSelection" :type="'users'" :isLastData="isLastData"></btnGroup>
      <pagination ref="pagination" :total="dataCount" class="block pages"></pagination>
    </div>
    <el-table :data="tableData" @selection-change="selectItem">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column prop="s_name" label="所属组织架构"></el-table-column>
      <el-table-column label="用户名" prop="username"></el-table-column>
      <el-table-column label="真实姓名" prop="realname"></el-table-column>
      <el-table-column label="备注" prop="remark"></el-table-column>
      <listStatus></listStatus>
      <listActions
        :toRouter="'usersEdit'"
        :deleteUrl="'admin/users/'"
        :isLastData="isLastData"
        :type="'users'"
      ></listActions>
    </el-table>
    <!-- <div class="pos-rel p-t-20">
      <btnGroup :selectedData="multipleSelection" :type="'users'" :isLastData="isLastData"></btnGroup>
      <pagination ref="pagination" :total="dataCount" class="block pages"></pagination>
    </div> -->
  </div>
</template>

<script>
import btnGroup from "../common/btnGroup";
import listStatus from "../common/listStatus";
import listActions from "../common/listActions";
import pagination from "../common/pagination";
import http from "../../assets/js/http";

export default {
  data() {
    return {
      tableData: [],
      dataCount: null,
      keywords: "",
      multipleSelection: [],
      isLastData: false
    };
  },
  methods: {
    search() {
      this.$refs.pagination.currentPage = 1;
      const query = {
        ...this.$route.query,
        ...{ keywords: this.keywords, page: 1 }
      };
      router.push({
        path: this.$route.path,
        query: query
      });
    },
    selectItem(val) {
      this.multipleSelection = val;
      this.isLastData =
        this.multipleSelection.length === this.tableData.length ? true : false;
    },
    getAllUsers() {
      this.loading = true;
      const data = {
        params: {
          keywords: this.keywords,
          page: this.$refs.pagination.currentPage,
          limit: this.$refs.pagination.limit
        }
      };
      this.apiGet("admin/users", data).then(res => {
        this.handelResponse(res, data => {
          this.tableData = data.list;
          this.dataCount = data.dataCount;
        });
      });
    },
    getKeywords() {
      let query = this.$route.query;
      this.keywords = query.keywords ? query.keywords : "";
    },
    init() {
      this.getKeywords();
      this.getAllUsers();
    }
  },
  mounted() {
    this.init();
  },
  computed: {
    addShow() {
      return _g.getHasRule("admin-users-store");
    }
  },
  watch: {
    $route(to, from) {
      this.init();
    },
    tableData(val) {
      this.isLastData = val.length === 1 ? true : false;
    }
  },
  components: {
    btnGroup,
    listStatus,
    listActions,
    pagination
  },
  mixins: [http]
};
</script>