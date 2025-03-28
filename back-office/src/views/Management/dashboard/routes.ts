import DashboardPage from './DashboardPage.vue'

import { Squares2X2Icon } from '@heroicons/vue/24/solid'

export default [
  {
    path: '/gestao/painel',
    name: 'ManagementDashboard',
    component: DashboardPage,
    meta: { requiresAuth: true, sidebar: { title: 'Painel', icon: Squares2X2Icon } },
  },
]
