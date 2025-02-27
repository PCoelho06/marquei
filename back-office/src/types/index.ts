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
