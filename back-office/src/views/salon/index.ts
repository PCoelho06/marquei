import agenda from './agenda/routes'

export default [
  {
    path: '/salao/:salonId',
    name: 'Salon',
    component: () => import('@/views/salon/RouterStore.vue'),
    meta: { requiresAuth: true },
    children: [...agenda],
  },
]
