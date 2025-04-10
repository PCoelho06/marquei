import { computed } from 'vue'

import { baseQuery } from '@/composables/engineQueries'

import type { ObjectKeysLibraries } from '@/types'

export const filtersClients = computed<ObjectKeysLibraries>(() => ({
  ...baseQuery.value,
  name: {
    key: 'name',
    label: 'Nome',
    action: 'default',
  },
  phone: {
    key: 'phone',
    label: 'Contacto',
    action: 'default',
  },
}))
