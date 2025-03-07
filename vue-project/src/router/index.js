import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LectureView from '../views/Lectures/View.vue'
import LectureCreate from '../views/Lectures/Create.vue'
import LectureEdit from '../views/Lectures/Edit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
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
  ],
})

export default router
