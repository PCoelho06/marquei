import dashboard from './dashboard/routes'
import salons from './salons/routes'
import resources from './resources/routes'

export default [
  {
    path: '/gestao',
    name: 'Management',
    component: () => import('@/views/management/RouterManagement.vue'),
    meta: { requiresAuth: true },
    children: [...dashboard, ...salons, ...resources],
  },
]
