import RouterResources from '@/views/management/resources/RouterResources.vue'
import ResourcesView from '@/views/management/resources/ResourcesView.vue'

export default [
  {
    path: '/gestao/recursos',
    name: 'Resources',
    component: RouterResources,
    meta: { requiresAuth: true, sidebar: { title: 'Recursos', icon: 'resources' } },
    children: [
      {
        path: '',
        name: 'ListResources',
        component: ResourcesView,
        meta: { sidebar: { title: 'Recursos' } },
      },
      //   {
      //     path: ':id',
      //     name: 'GetResource',
      //     meta: { requiresOwnership: true },
      //     component: ResourceView,
      //   },
    ],
  },
]
