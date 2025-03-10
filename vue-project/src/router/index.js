import { createRouter, createWebHistory } from 'vue-router'
import LectureView from '../views/Lectures/View.vue'
import LectureCreate from '../views/Lectures/Create.vue'
import LectureEdit from '../views/Lectures/Edit.vue'
import Register from '@/components/Register.vue'
import Login from '@/components/Login.vue'
import Dashboard from '@/views/Master/Dashboard.vue'
import Home from '@/views/Master/Home.vue'
import UserHome from '@/views/User/UserHome.vue'
import UserManager from '@/views/Master/UserManager.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin',
      name: 'HomePage',
      component: Home,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/lectures',
      name: 'lectures',
      component: LectureView
    },
    {
      path: '/lectures/create',
      name: 'lectureCreate',
      component: LectureCreate
    },
    {
      path: '/lectures/:id/edit',
      name: 'lectureEdit',
      component: LectureEdit
    },
    {
      path:'/register',
      name: 'Register',
      component: Register
    },
    {
      path:'/login',
      name: 'Login',
      component: Login
    },
    {
      path:'/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path:'/usermanager',
      name: 'UserManager',
      component: UserManager
    },
    {
      path:'/',
      name: 'User',
      component: UserHome
    }
  ],
})

export default router
