import DashboardPage from './DashboardPage.vue'

export default [
  {
    path: '/gestao/painel',
    name: 'ManagementDashboard',
    component: DashboardPage,
    meta: { requiresAuth: true, sidebar: { title: 'Painel', icon: 'dashboard' } },
  },
]
