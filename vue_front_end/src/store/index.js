import { createStore } from 'vuex'
import { getToken, getRefreshToken, setToken, setRefreshToken, removeToken, removeRefreshToken } from '@/utils/auth'
import { login } from '@/api/user'

export default createStore({
  state: {
    token: getToken(),
    refresh_token: getRefreshToken()
  },
  getters: {
    token: state => state.token,
    refresh_token: state => state.refresh_token,
  },
  mutations: {
    SET_TOKEN: (state, token) => {
      state.token = token
    },
    SET_REFRESH_TOKEN: (state, refresh_token) => {
      state.refresh_token = refresh_token
    }
  },
  actions: {
    login({ commit }, data){
      commit('SET_TOKEN', '')
      commit('SET_REFRESH_TOKEN', '')
      removeToken()
      removeRefreshToken()

      return new Promise((resolve, reject) => {
        login(data.account, data.password).then(response => {
          const d = response.data

          if (d.result === 'success') {
            commit('SET_TOKEN', d.access_token)
            commit('SET_REFRESH_TOKEN', d.refresh_token)
            setToken(d.access_token)
            setRefreshToken(d.refresh_token)
            resolve(d)
          } else {
            reject(d.msg)
          }
        }).catch(() => {
          reject('ERROR_9999')
        })
      })
    }
  },
  modules: {
  }
})
