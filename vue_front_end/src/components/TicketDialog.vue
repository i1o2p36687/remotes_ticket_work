<template>
	<el-dialog
		v-model="dialogVisible"
		:before-close="closeDialog"
		:title="(form.id !== '' ? '修改' : '新增')+' Ticket'">

		<el-form 
			:model="form"
			ref="ticketForm"
			:rules="rules">

			<el-form-item label="類型" prop="type">
				<el-select v-model="form.type" placeholder="請選擇類型" size="large" :disabled="form.id !== ''">
					<el-option v-if="chkActionPermission('create_bug') || form.id !== ''" label="錯誤" value="bug"></el-option>
					<el-option v-if="chkActionPermission('create_test') || form.id !== ''" label="測試" value="test"></el-option>
					<el-option v-if="chkActionPermission('create_feature') || form.id !== ''" label="功能" value="feature"></el-option>
				</el-select>
			</el-form-item>

			<el-form-item label="標題" prop="title">
				<el-input v-model="form.title" autocomplete="off" />
			</el-form-item>

			<el-form-item label="描述" prop="description">
				<el-input v-model="form.description" type="textarea" />
			</el-form-item>

			<el-form-item label="是否優先" prop="priority">
				<el-switch v-model="form.priority" />
			</el-form-item>

			<el-form-item>
				<el-button type="primary" @click="form.id === '' ? create() : update() " :loading="loading" >確定</el-button>
				<el-button type="primary" v-if="form.status === 1" @click="updateResolve" :loading="loading_resolve" >已解決</el-button>
				<el-button type="danger" v-if="form.status === 1" @click="updateDelete" :loading="loading_delete" >刪除</el-button>
			</el-form-item>
		</el-form>

		<!--<div v-if="form.id !== ''">
			<el-divider />
			<el-form 
				:model="form_message">

				<el-form-item label="留言">
					<el-input
						v-model="form_message.message"
						placeholder="請輸入訊息"
						show-word-limit
						type="textarea"
					/>
				</el-form-item>
				<el-form-item>
					<el-button type="primary" @click="createMessage" :loading="loading_message" >確定</el-button>
				</el-form-item>

			</el-form>
		</div>-->
	</el-dialog>
</template>

<script>
import { createTicket, updateTicket, resolveTicket, deleteTicket } from '@/api/ticket'

export default {
	name: 'TicketDialog',
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
			loading_message: false,
			loading_resolve: false,
			loading_delete: false,
			form: {
				id: '',
				type: '',
				title: '',
				description: '',
				priority: false,
				status: 0
			},
			form_message:{
				id: '',
				message: ''
			},
			rules: {
				type: [
					{ required: true, message: '請選擇類型', trigger: 'blur' }
				],
				title: [
					{ required: true, message: '請輸入標題', trigger: 'blur' }
				],
				description: [
					{ required: true, message: '請輸入描述', trigger: 'blur' }
				]
			}
		}
	},
	computed: {
		user: function(){
			return this.$store.getters.user
		}
	},
	watch: {
		dialogVisible(val) {
			this.$emit('onEditTicketVisibleChange', val)
		},
		visible(val) {
			this.dialogVisible = val
		},
	},
	methods: {
		chkActionPermission(action) {
			return this.user ? this.user.permissions.indexOf(action) > -1 : false
		},
		emitToFunction(ticket='') {
			this.resData()

			if(ticket !== ''){
				this.form.id = ticket.id
				this.form.type = ticket.type
				this.form.title = ticket.title
				this.form.description = ticket.description
				this.form.priority = ticket.priority === 1
				this.form.status = ticket.status
			}

		},
		create(){
			this.$refs.ticketForm.validate(valid => {
				if (valid) {
					this.loading = true
					createTicket(this.form).then(response => {
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
			this.$refs.ticketForm.validate(valid => {
				if (valid) {
					this.loading = true
					updateTicket(this.form).then(response => {
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
		updateResolve(){
			this.loading_resolve = true
			const d = {id: this.form.id, type: this.form.type}
			resolveTicket(d).then(response => {
				const data = response.data
				this.loading_resolve = false

				if(data.result === 'success'){
					this.$emit('reList')
					this.closeDialog()
				}

				this.$emit('showNotify', data.result, data.msg)
			})
		},
		updateDelete(){
			this.loading_delete = true
			const d = {id: this.form.id, type: this.form.type}
			deleteTicket(d).then(response => {
				const data = response.data
				this.loading_delete = false

				if(data.result === 'success'){
					this.$emit('reList')
					this.closeDialog()
				}

				this.$emit('showNotify', data.result, data.msg)
			})
		},
		closeDialog(){
			this.dialogVisible = false
		},
		resData(){
			this.form = {
				id: '',
				type: '',
				title: '',
				description: '',
				priority: false,
				status: 0
			}
			this.form_message = {
				id: '',
				message: ''
			}
		}
	}
}
</script>