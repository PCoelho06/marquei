import RouterResources from '@/views/management/resources/RouterResources.vue'
import ResourcesView from '@/views/management/resources/ResourcesView.vue'
import ResourceView from '@/views/management/resources/ResourceView.vue'

import { RectangleGroupIcon } from '@heroicons/vue/24/solid'
import ManageResourceAvailabilitiesView from './ManageResourceAvailabilitiesView.vue'

export default [
  {
    path: '/gestao/recursos',
    name: 'Resources',
    component: RouterResources,
    meta: { requiresAuth: true, sidebar: { title: 'Recursos', icon: RectangleGroupIcon } },
    children: [
      {
        path: '',
        name: 'ListResources',
        component: ResourcesView,
        meta: { sidebar: { title: 'Recursos' } },
      },
      {
        path: ':id',
        name: 'GetResource',
        meta: { requiresOwnership: true },
        component: ResourceView,
      },
      {
        path: ':id/horarios-de-disponibilidade',
        name: 'ManageResourceAvailabilities',
        meta: { requiresOwnership: true },
        component: ManageResourceAvailabilitiesView,
      },
    ],
  },
]
