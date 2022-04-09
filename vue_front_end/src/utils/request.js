import axios from 'axios'
import { ElMessage } from 'element-plus'
import store from '@/store'
import { getToken, getTokenExpire } from '@/utils/auth'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_URL, // api 的 base_url
  timeout: 60000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    if (getToken()) {
      config.headers['Authorization'] = 'Bearer '+getToken()
    }
    return config
  },
  error => {
    // Do something with request error
    console.log(error) // for debug
    Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  response => response,
  error => {
    const now = Date.now()
    if(error.response.status === 401 && getToken() && getTokenExpire() && now >= parseInt(getTokenExpire())){
      store.dispatch('refreshToken').then(r => {
        if(r.result === 'success'){
          ElMessage({
            message: '登入Token刷新成功 三秒後重新整理',
            type: 'success',
            duration: 5 * 1000
          })
          setTimeout('window.location.reload()', 3000)
        }else{
          store.dispatch('FedLogOut').then(() => {
            window.location.reload()
          })
        }
      })
    }else{
      if(error.response.status === 401 || error.response.status === 403){
        store.dispatch('FedLogOut').then(() => {
          window.location.reload()
        })
      }
    }

    ElMessage({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
