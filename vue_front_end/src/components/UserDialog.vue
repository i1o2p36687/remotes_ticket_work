<template>
	<el-dialog
		v-model="dialogVisible"
		:before-close="closeDialog"
		:title="(form.id !== '' ? '修改' : '新增')+'使用者'">

		<el-form 
			:model="form"
			ref="userForm"
			:rules="rules">
			<el-form-item label="帳號" prop="account">
        <el-input v-model="form.account" :disabled="form.id !== ''" autocomplete="off" />
      </el-form-item>
      <el-form-item v-if="form.id === ''" label="密碼" prop="password">
        <el-input v-model="form.password" type="password" autocomplete="off" />
      </el-form-item>
			<el-form-item label="名稱" prop="name">
        <el-input v-model="form.name" autocomplete="off" />
      </el-form-item>
			<el-form-item v-if="form.role_id != 1" label="名稱" prop="role_id">
        <el-select v-model="form.role_id" placeholder="請選擇角色" size="large">
					<el-option
						v-for="item in roles"
						:key="item.id"
						:label="item.name"
						:value="item.id"
					/>
				</el-select>
      </el-form-item>
		</el-form>

		<template #footer>
      <span class="dialog-footer">
        <el-button @click="closeDialog" >關閉</el-button>
        <el-button type="primary" @click="form.id === '' ? create() : update() " :loading="loading" >確定</el-button>
      </span>
    </template>
	</el-dialog>
</template>

<script>
import { fetchRoleList, createUser, updateUser } from '@/api/user'

export default {
	name: 'UserDialog',
	props: {
    visible: {
      type: Boolean,
      default: false
    }
  },
	data() {
		return {
			dialogVisible: this.visible,
			loading: false,
			form: {
				id: '',
				account: '',
				password: '',
				name: '',
				role_id: ''
			},
			rules: {
        account: [
          { required: true, message: '請輸入帳號', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '請輸入密碼', trigger: 'blur' }
        ],
				name: [
          { required: true, message: '請輸入名稱', trigger: 'blur' }
        ],
				role_id: [
          { required: true, message: '請選擇角色', trigger: 'blur' }
        ],
      },
			roles: []
		}
	},
	watch: {
    dialogVisible(val) {
      this.$emit('onEditUserVisibleChange', val)
    },
    visible(val) {
      this.dialogVisible = val
    },
	},
	methods: {
		getRoles(){
			fetchRoleList().then(response=> {
        this.roles = response.data.list
      })
		},
		emitToFunction(user='') {
      this.resData()

			if(user !== ''){
				this.form.id = user.id
				this.form.account = user.account
				this.form.name = user.name
				this.form.role_id = user.role_id
				this.form.password = 'create'
			}

			this.getRoles()
    },
		create(){
      this.$refs.userForm.validate(valid => {
        if (valid) {
					this.loading = true
          createUser(this.form).then(response => {
						const data = response.data
            this.loading = false

						if(data.result === 'success'){
							this.$emit('reList')
							this.closeDialog()
						}

						this.$emit('showNotify', data.result, data.msg)
          })
        }
      })
		},
		update(){
      this.$refs.userForm.validate(valid => {
        if (valid) {
					this.loading = true
          updateUser(this.form).then(response => {
						const data = response.data
            this.loading = false

						if(data.result === 'success'){
							this.$emit('reList')
							this.closeDialog()
						}

						this.$emit('showNotify', data.result, data.msg)
          })
        }
      })
		},
		closeDialog(){
      this.dialogVisible = false
    },
		resData(){
			this.form = {
				id: '',
				account: '',
				password: '',
				name: '',
				role_id: ''
			}
		}
	}
}
</script>