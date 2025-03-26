import { computed } from 'vue'

import { baseQuery } from '@/composables/engineQueries'

import type { ObjectKeysLibraries } from '@/types'

export const filtersResources = computed<ObjectKeysLibraries>(() => ({
  ...baseQuery.value,
  name: {
    key: 'name',
    label: 'Nome',
    action: 'default',
  },
  type: {
    key: 'type',
    label: 'Tipo',
    list: true,
    action: 'default',
  },
  salon: {
    key: 'salon',
    label: 'Salão',
    list: true,
    action: 'default',
  },
}))
