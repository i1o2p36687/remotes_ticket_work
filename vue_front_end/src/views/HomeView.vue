<template>
  <!--<div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  </div>-->
  <div class="top">
    <el-button v-if="chkActionPermission('create_user')" type="primary" @click="$router.push({ path: '/users' })">會員管理</el-button>
    <el-button v-if="chkActionPermission('create_bug') || chkActionPermission('create_test') || chkActionPermission('create_feature')" type="primary" @click="handleTicketCreate">新增 Ticket</el-button>
    <el-button v-if="user !== ''" type="info" @click="logout">登出</el-button>
  </div>
  <el-divider />
    <span class="user_title">{{ user.name }}</span><el-tag>{{ user.role }}</el-tag>
  <el-divider />

  <el-table class="table" :data="list" v-loading="listLoading">
      <el-table-column label="類型">
        <template #default="props">
          <el-tag :type="type[props.row.type]['button']">
            {{ type[props.row.type]['name'] }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="是否優先">
        <template #default="props">
          <el-tag v-if="props.row.priority === 1" type="warning">
            是
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="title" label="標題"  />
      <el-table-column label="狀態">
        <template #default="props">
          <el-button :type="props.row.status === 1 ? 'warning' : 'success'">
            {{ props.row.status === 1 ? '處理中' : '已解決' }}
          </el-button>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template #default="props">
          <el-button type="primary" @click="handleTicketUpdate(props.row)">內容</el-button>
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

  <editTicket ref="editTicket" :visible="showEditTicketDialog" @reList="getList" @onEditTicketVisibleChange="closedEditTicketDialog" @chkPermission="chkActionPermission" @showNotify="showNotify" />
</template>

<script>
// @ is an alias to /src
import { fetchList } from '@/api/ticket'
import editTicket from '../components/TicketDialog'

export default {
  name: 'HomeView',
  components: {
    editTicket
  },
  data() {
    return {
      showEditTicketDialog: false,
      total: 0,
      list: [],
      search: {
        page: 1,
        limit: 15
      },
      listLoading: false,
      type: {
        bug: {'name': '錯誤', 'button': 'danger'},
        test: {'name': '測試', 'button': 'info'},
        feature: {'name': '功能', 'button': 'primary'},
      }
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
    handleTicketCreate(){
      this.showEditTicketDialog = true,
      this.$refs.editTicket.emitToFunction()
    },
    handleTicketUpdate(ticket_data){
      this.showEditTicketDialog = true,
      this.$refs.editTicket.emitToFunction(ticket_data)
    },
    closedEditTicketDialog(val) {
      this.showEditTicketDialog = val
    },
    logout(){
      this.$store.dispatch('FedLogOut').then(() => {
        window.location.reload()
      })
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
  }
}
</script>

<style>
.user_title{
  font-size: 18px;
  font-weight: bold;
}
.table{
  width: 100%;
  padding: 15px;
  margin: auto;
}
.top{
  text-align: left;
}
</style>