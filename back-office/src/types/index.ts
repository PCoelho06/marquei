export interface Payload {
  token: string
}

export type SidebarCategories = 'Painel' | 'Relatórios' | 'Financeiro'
export type SidebarItems = 'Empregados' | 'Material' | 'Registar'

export interface ListSettings {}

export interface JWTPayload {
  exp: number
  iat: number
  id: number
  salons: number[]
  roles: string[]
  currentMode: 'management' | 'store'
  role?: string
  permissions?: string[]
  currentSalon?: number
}

interface RouteMeta {
  sidebar?: {
    icon: string
    title: string
  }
}

interface SidebarMeta {
  sidebar: {
    icon: string
    title: string
  }
}

interface RouteBase {
  path: string
  name: string
  component: any
  children?: Route[]
}

export interface Route extends RouteBase {
  meta?: RouteMeta
}

export interface SidebarRoute extends RouteBase {
  meta: SidebarMeta
}

export interface SidebarMenuItem {
  label: string
  route: string
}

export interface SidebarMenuCategory extends SidebarMenuItem {
  children?: SidebarMenuItem[]
  icon: string
}

export interface ModalContent {
  title: string
  content: string
  dismiss: string
  validate?: string
  action?: () => void
}

export interface SelectOption {
  value: string | number
  label: string
}
