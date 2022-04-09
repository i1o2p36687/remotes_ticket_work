<template>
  <!--<div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  </div>-->
  <el-row class="mb-4">
    <el-button v-if="chkActionPermission('create_user')" type="primary" @click="$router.push({ path: '/users' })">會員管理</el-button>
    <el-button v-if="user !== ''" type="info" @click="logout">登出</el-button>
  </el-row>
</template>

<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'HomeView',
  components: {
    //HelloWorld
  },
  computed: {
    user: function(){
      return this.$store.getters.user
    }
  },
  created() {
  },
  methods: {
    /*test(){
      console.log('role', this.$store.getters.user)
    },*/
    chkActionPermission(action) {
      return this.user ? this.user.permissions.indexOf(action) > -1 : false
    },
    logout(){
      this.$store.dispatch('FedLogOut').then(() => {
        window.location.reload()
      })
    }
  }
}
</script>
