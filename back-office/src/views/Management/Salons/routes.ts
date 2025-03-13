import RouterSalons from '@/views/management/salons/RouterSalons.vue'
import SalonsView from '@/views/management/salons/SalonsView.vue'
import SalonView from '@/views/management/salons/SalonView.vue'
import HandleSalonView from '@/views/management/salons/HandleSalonView.vue'
import HandleBusinessHoursView from '@/views/management/salons/HandleBusinessHoursView.vue'
import ForfaitsView from '@/views/management/salons/subscriptions/ForfaitsView.vue'
import SuccessView from './subscriptions/SuccessView.vue'
import ManageSubscription from './subscriptions/ManageSubscription.vue'

export default [
  {
    path: '/gestao/saloes',
    name: 'Salons',
    component: RouterSalons,
    meta: { requiresAuth: true, sidebar: { title: 'Salões', icon: 'shop' } },
    children: [
      {
        path: '',
        name: 'ListSalons',
        component: SalonsView,
        meta: { sidebar: { title: 'Salões' } },
      },
      {
        path: 'registar',
        name: 'CreateSalon',
        component: HandleSalonView,
      },
      {
        path: ':id',
        name: 'GetSalon',
        meta: { requiresOwnership: true },
        component: SalonView,
      },
      {
        path: ':id/editar',
        name: 'EditSalon',
        meta: { requiresOwnership: true },
        component: HandleSalonView,
      },
      {
        path: ':id/horarios-de-funcionamento',
        name: 'HandleBusinessHours',
        meta: { requiresOwnership: true },
        component: HandleBusinessHoursView,
      },
      {
        path: ':id/escolher-pacote',
        name: 'HandleForfait',
        meta: { requiresOwnership: true },
        component: ForfaitsView,
      },
      {
        path: ':id/successo',
        name: 'SucceededPayment',
        meta: { requiresOwnership: true },
        component: SuccessView,
      },
      {
        path: ':id/gerir-subscricao',
        name: 'ManageSubscription',
        meta: { requiresOwnership: true },
        component: ManageSubscription,
      },
    ],
  },
]
