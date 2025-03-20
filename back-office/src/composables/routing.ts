import type { Route, SidebarRoute, SidebarMenuCategory, SidebarMenuItem } from '@/types'
import viewsRouter from '@/views'

import NotFound from '@/views/errors/NotFound.vue'

const routing: object = Object.entries(viewsRouter).reduce((acc: object, [key, val]) => {
  acc = { ...acc, [key]: val }
  return acc
}, {})

export const routes = Object.values(routing).reduce(
  (acc, routes: Route[]): void => {
    acc.push(...routes)
    return acc
  },
  [{ path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }],
)

const managementRoutes = routes.filter((route: Route): boolean => {
  return route.name === 'Management'
})[0].children

export const managementSidebarMenuItems = managementRoutes
  .filter((route: Route) => {
    return route.meta?.sidebar
  })
  .map((route: SidebarRoute) => {
    const managementSidebarMenuItem: SidebarMenuCategory = {
      icon: route.meta.sidebar.icon,
      label: route.meta.sidebar.title,
      route: route.path,
    }

    if (route.children) {
      managementSidebarMenuItem.children = route.children
        .filter((child: Route) => {
          return child.meta?.sidebar
        })
        .map((child: Route) => {
          return {
            label: child.meta?.sidebar?.title,
            route: route.path + '/' + child.path,
          } as SidebarMenuItem
        })
    }
    return managementSidebarMenuItem
  })
