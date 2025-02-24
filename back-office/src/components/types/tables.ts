interface Column {
  key: string
  label: string
  editable?: boolean
}

export interface Props<T> {
  items: T[]
  columns: Column[]
}

export interface SortConfig {
  key: string
  direction: 'asc' | 'desc'
}
