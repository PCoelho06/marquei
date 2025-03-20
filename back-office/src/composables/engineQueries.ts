import { computed } from 'vue'

import type { Libraries, ObjectKeysLibraries, QueryTag } from '@/types'
import type { LocationQuery } from 'vue-router'

export const baseQuery = computed<ObjectKeysLibraries>(() => ({
  page: {
    key: 'page',
    label: 'Page',
    action: 'default',
  },
  size: {
    key: 'size',
    label: 'Size',
    action: 'default',
  },
  sort: {
    key: 'sort',
    label: 'Sort',
    action: 'sort',
  },
}))

interface ObjectKeysQuery {
  [key: string]: string | undefined
}

export const engineQueries = () => ({
  /**
   *  Format httpQuery to be used in the VueRouter query.
   *
   *  @param query           object     httpQuery to be formatted containing all filters params
   *
   *  @returns               object     formatted query to be used in the VueRouter query
   */
  formatForRouter: (query: object) =>
    Object.entries(query).reduce((acc: ObjectKeysQuery, [key, value]) => {
      if (value === undefined || value === null || value === 'null' || value === '') {
        acc[key] = undefined
        return acc
      }
      if (Array.isArray(value)) {
        if (value.length) {
          acc[key] = value
            .map((el) => {
              if (typeof el === 'object') return el.code
              return el
            })
            .join(',')
        } else {
          acc[key] = undefined
        }
        return acc
      }
      acc[key] = value
      return acc
    }, {}),

  /**
   *  Format VueRouter query to be used as an httpQuery.
   *
   *  @param query           object     VueRouter query to be formatted containing all filters params
   *  @param lib             ObjectKeysLibrairies                                     Possible filters and settings for the current query
   *
   *  @returns               object     formatted query to be used as an httpQuery
   */
  formatFromRouter: (query: object, lib: ObjectKeysLibraries) =>
    Object.entries(query).reduce((acc: any, [key, value]) => {
      if (!value || !lib[key]) return acc
      const { list } = lib[key]
      if (list) {
        acc[key] = value.split(',').map((label: string) => label)
        return acc
      }
      acc[key] = value
      return acc
    }, {}),

  // updatePage: (query: any, val: number, totalPages: number, fn: Function): any => {
  //   query.page = Math.min(Math.max(0, val - 1), totalPages - 1)
  //   fn(undefined, 'updtPage')
  //   return query
  // },

  // updateSize: (query: any, val: number | string, fn: Function): any => {
  //   query.size = val
  //   query.page = 0
  //   fn(undefined, 'updtSize')
  //   return query
  // },

  // updateSort: (query: any, val: any, lib: any, fn: Function): any => {
  //   query.sort = [
  //     `${!lib[val.sortField] ? val.sortField : lib[val.sortField]},${val.sortDirection}`,
  //   ]
  //   query.page = 0
  //   fn(undefined, 'updtSort')
  //   return query
  // },
})
