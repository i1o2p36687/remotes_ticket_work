<template>
  <div class="login">
    <el-form
      ref="loginForm"
      :model="loginForm"
      status-icon
      :rules="rules"
      label-width="120px"
      class="demo-ruleForm"
    >
      <el-form-item label="帳號" prop="account">
        <el-input v-model="loginForm.account" />
      </el-form-item>
      <el-form-item label="密碼" prop="password">
        <el-input v-model="loginForm.password" type="password" autocomplete="off" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="login()" :loading="loading">登入</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      loading: false,
      loginForm: {
        account: '',
        password: ''
      },
      rules: {
        account: [
          { required: true, message: '請輸入帳號', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '請輸入密碼', trigger: 'blur' }
        ],
      },
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.$store.dispatch('login', this.loginForm).then(response => {
            this.loading = false
            this.$router.push({ path: '/' })
            
            this.$notify({
              title: '成功',
              message: '登入成功',
              type: response.result,
              duration: 3000
            })
          }).catch((msg) => {
            this.loading = false
            this.$notify({
              title: '錯誤',
              message: msg,
              type: 'error',
              duration: 3000
            })
          })
        }
      })
    }
  }
}
</script>

<style>
.login{
    width: 50%;
    margin: auto;
    border: 1px solid #e1e1e1;
    border-radius: 16px;
    padding: 12px;
}
</style>