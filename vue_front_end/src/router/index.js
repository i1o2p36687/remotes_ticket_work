import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import store from '../store'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/LoginView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

//判斷是否要登入
router.beforeEach((to) => {
  if (store.getters.token) {
    if (to.name === 'Login') {
      return { name: 'home' }
    }
  }else{
    if (to.name !== 'Login') {
      return { name: 'Login' }
    }
  }

  return true
})

export default router
