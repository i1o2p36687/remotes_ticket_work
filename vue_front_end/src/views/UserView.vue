<template>
  <div>
    <el-page-header @back="goBack" />

    <div class="top">
      <el-button v-if="chkActionPermission('create_user')" type="primary" @click="handleCreate()">新增</el-button>
    </div>

    <el-table class="table" v-if="chkActionPermission('view_user')" :data="list" v-loading="listLoading">
      <el-table-column prop="id" label="key" />
      <el-table-column prop="account" label="帳號"  />
      <el-table-column prop="name" label="姓名" />
      <el-table-column label="狀態">
        <template #default="props">
          <el-button :type="props.row.status === 1 ? 'success' : 'danger'">
            {{ props.row.status === 1 ? '啟用' : '停用' }}
          </el-button>
        </template>
      </el-table-column>
      <el-table-column label="修改">
        <template #default="props">
          <el-button type="primary" @click="handleUpdate(props.row)">修改</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-pagination 
      background 
      layout="prev, pager, next" 
      :current-page="search.page"
      :total="total" 
      :page-size="search.limit"
      :pager-count="10" 
      @current-change="current_change" />

    <editUser ref="editUser" :visible="showEditUserDialog" @reList="getList" @onEditUserVisibleChange="closedEditUserDialog" @showNotify="showNotify" />
  </div>
</template>

<script>
import { fetchList } from '@/api/user'
import editUser from '../components/UserDialog'

export default {
  name: 'UserView',
  components: {
    editUser
  },
  data() {
    return {
      total: 0,
      list: [],
      search: {
        page: 1,
        limit: 15,
        status: ''
      },
      listLoading: false,
      showEditUserDialog: false,
    }
  },
  computed: {
    user: function(){
      return this.$store.getters.user
    }
  },
  created() {
    this.getList()
  },
  methods: {
    chkActionPermission(action) {
      return this.user ? this.user.permissions.indexOf(action) > -1 : false
    },
    getList(){
      this.listLoading = true
      this.list = []

      fetchList(this.search).then(response=> {
        this.listLoading = false
        this.list = response.data.list
        this.total = response.data.count
      }).catch((msg) => {
        this.listLoading = false
        this.showNotify('error', msg)
      })
    },
    current_change(val){
      this.search.page = val
      this.getList()
    },
    handleCreate(){
      this.showEditUserDialog = true,
      this.$refs.editUser.emitToFunction()
    },
    handleUpdate(user_data){
      this.showEditUserDialog = true,
      this.$refs.editUser.emitToFunction(user_data)
    },
    closedEditUserDialog(val) {
      this.showEditUserDialog = val
    },
    showNotify(type, msg){
      type = type === 'fail' ? 'error' : type
      this.$notify({
        title: type === 'error' ? '錯誤' : '成功',
        message: msg,
        type: type,
        duration: 3000
      })
    },
    goBack(){
      history.back()
    }
  }
}
</script>

<style>
.table{
  width: 100%;
  padding: 15px;
  margin: auto;
}
.top {
  margin: 10px 0;
  text-align: left;
}
</style>