import AgendaView from './AgendaView.vue'

export default [
  {
    path: '/salao/:salonId/agenda',
    name: 'SalonAgenda',
    component: AgendaView,
    meta: { requiresAuth: true },
  },
]
