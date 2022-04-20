import Vue from 'vue'
import VueRouter, { RouteConfig } from 'vue-router'
import Home from '../views/Home.vue'
import Instructors from "@/views/Instructors.vue";
import Labs from "@/views/Labs.vue";

Vue.use(VueRouter)

const routes: Array<RouteConfig> = [
  { path: '/', name: 'Home', component: Home },
  { path: '/instructors', name: 'Instructors', component: Instructors },
  { path: '/labs', name: 'Labs', component: Labs }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
