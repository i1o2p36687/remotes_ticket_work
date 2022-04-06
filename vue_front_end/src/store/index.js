import { createStore } from 'vuex'

export default createStore({
  state: {
    token: '',
    refresh_token: ''
  },
  getters: {
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
    getToken({ commit }, token){
      commit('SET_TOKEN', token)
    },
    getRefreshToken({ commit }, refresh_token){
      commit('SET_TOKEN', refresh_token)
    }
  },
  modules: {
  }
})
