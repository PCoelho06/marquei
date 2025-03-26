import { computed } from 'vue'

import { baseQuery } from '@/composables/engineQueries'

import type { ObjectKeysLibraries } from '@/types'

export const filtersServices = computed<ObjectKeysLibraries>(() => ({
  ...baseQuery.value,
  name: {
    key: 'name',
    label: 'Nome',
    action: 'default',
  },
  minDuration: {
    key: 'min-duration',
    label: 'Duração minima',
    action: 'default',
  },
  maxDuration: {
    key: 'max-duration',
    label: 'Duração máxima',
    action: 'default',
  },
  minPrice: {
    key: 'min-price',
    label: 'Preço minimo',
    action: 'default',
  },
  maxPrice: {
    key: 'max-price',
    label: 'Preço máximo',
    action: 'default',
  },
}))

// export const filtersSalons = computed<ObjectKeysLibraries>(() => ({
//   ...baseQuery.value,
//   name: {
//     key: 'name',
//     label: 'Nome',
//     action: 'default',
//   },
//   type: {
//     key: 'type',
//     label: 'Tipo',
//     list: true,
//     action: 'default',
//   },
//   salon: {
//     key: 'salon',
//     label: 'Salão',
//     list: true,
//     action: 'default',
//   },
// }))
