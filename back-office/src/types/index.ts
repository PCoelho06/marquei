import type { ResourceType } from './resources'

export interface Payload {
  token: string
}

export interface BaseQuery {
  sort?: Array<string>
  page?: number
  limit?: number
}

export interface ResourceQuery extends BaseQuery {
  salon?: number
  type?: ResourceType
  name?: string
}

export type SidebarCategories = 'Painel' | 'Relatórios' | 'Financeiro'
export type SidebarItems = 'Empregados' | 'Material' | 'Registar'

export interface Pageable {
  sort: []
  offset: number
  pageNumber: number
  pageLimit: number
  paged: boolean
  unpaged: boolean
}
export interface ListSettings {
  pageable: Pageable
  last: boolean
  totalPages: number
  totalElements: number
  limit: number
  number: number
  sort: []
  first: boolean
  numberOfElements: number
  empty: boolean
}

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

export interface Libraries {
  key: string
  label: string
  list?: boolean
  action: 'sort' | 'default'
}

export interface ObjectKeysLibraries {
  [key: string]: Libraries
}

export interface QueryTag {
  label: string | null
  key: string | null
}
