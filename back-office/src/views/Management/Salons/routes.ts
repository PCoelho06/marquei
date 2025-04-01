import RouterSalons from '@/views/management/salons/RouterSalons.vue'
import SalonsListView from '@/views/management/salons/SalonsListView.vue'
import SalonView from '@/views/management/salons/SalonView.vue'
import HandleSalonView from '@/views/management/salons/HandleSalonView.vue'
import ManageBusinessHoursView from '@/views/management/salons/ManageBusinessHoursView.vue'
import ForfaitsView from '@/views/management/salons/subscriptions/ForfaitsView.vue'
import SuccessView from './subscriptions/SuccessView.vue'
import ManageSubscription from './subscriptions/ManageSubscription.vue'

import { BuildingStorefrontIcon } from '@heroicons/vue/24/solid'

export default [
  {
    path: '/gestao/saloes',
    name: 'Salons',
    component: RouterSalons,
    meta: { requiresAuth: true, sidebar: { title: 'Salões', icon: BuildingStorefrontIcon } },
    children: [
      {
        path: '',
        name: 'ListSalons',
        component: SalonsListView,
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
        name: 'ManageBusinessHours',
        meta: { requiresOwnership: true },
        component: ManageBusinessHoursView,
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
