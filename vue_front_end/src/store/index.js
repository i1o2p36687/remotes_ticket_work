import { createStore } from 'vuex'
import { getToken, getTokenExpire, getRefreshToken, setToken, setTokenExpire, setRefreshToken, removeToken, removeRefreshToken, removeTokenExpire } from '@/utils/auth'
import { login, refreshToken, getUserInfo } from '@/api/user'

export default createStore({
  state: {
    token: getToken(),
    token_expire: getTokenExpire(),
    refresh_token: getRefreshToken(),
    user: ''
  },
  getters: {
    token: state => state.token,
    token_expire: state => state.token_expire,
    refresh_token: state => state.refresh_token,
    user: state => state.user,
  },
  mutations: {
    SET_TOKEN: (state, token) => {
      state.token = token
    },
    SET_TOKEN_EXPIRE: (state, token_expire) => {
      state.token = token_expire
    },
    SET_REFRESH_TOKEN: (state, refresh_token) => {
      state.refresh_token = refresh_token
    },
    SET_USER: (state, user) => {
      state.user = user
    }
  },
  actions: {
    //get login token
    login({ commit }, data){
      commit('SET_TOKEN', '')
      commit('SET_TOKEN_EXPIRE', 0)
      commit('SET_REFRESH_TOKEN', '')
      commit('SET_USER', [])
      removeToken()
      removeTokenExpire()
      removeRefreshToken()

      return new Promise((resolve, reject) => {
        login(data.account, data.password).then(response => {
          const d = response.data

          if (d.result === 'success') {
            const token_expire = Date.now() + d.expires_in * 1000

            commit('SET_TOKEN', d.access_token)
            commit('SET_TOKEN_EXPIRE', token_expire)
            commit('SET_REFRESH_TOKEN', d.refresh_token)
            setToken(d.access_token)
            setTokenExpire(token_expire)
            setRefreshToken(d.refresh_token)
            resolve(d)
          } else {
            reject(d.msg)
          }
        }).catch(err => {
          reject(err)
        })
      })
    },
    // refresh token
    async refreshToken({ commit, state }) {
      return new Promise((resolve, reject) => {
        refreshToken(state.refresh_token).then(response => {
          const d = response.data

          if (d.result === 'success') {
            const token_expire = Date.now() + d.expires_in * 1000

            commit('SET_TOKEN', d.access_token)
            commit('SET_TOKEN_EXPIRE', token_expire)
            commit('SET_REFRESH_TOKEN', d.refresh_token)
            setToken(d.access_token)
            setTokenExpire(token_expire)
            setRefreshToken(d.refresh_token)
            resolve(d)
          } else {
            resolve(d)
          }
        }).catch(err => {
          reject(err)
        })
      })
    },
    getUserInfo({ commit, state }) {
      return new Promise((resolve, reject) => {
        getUserInfo(state.token).then(response => {
          const d = response.data

          commit('SET_USER', d)
          resolve(d)
        }).catch(error => {
          reject(error)
        })
      })
    },
    FedLogOut({ commit }) {
      return new Promise(resolve => {
        commit('SET_TOKEN', '')
        commit('SET_TOKEN_EXPIRE', 0)
        commit('SET_REFRESH_TOKEN', '')
        commit('SET_USER', [])

        removeToken()
        removeTokenExpire()
        removeRefreshToken()
        resolve()
      })
    },
  },
  modules: {
  }
})

