import router from '@/router'

import type { BuilderContext, Internals } from '@/types/builder'
import {
  AxiosError,
  type AxiosInstance,
  type AxiosResponse,
  type InternalAxiosRequestConfig,
} from 'axios'

import axios from 'axios'

import { useAuthStore } from '@/stores/auth'

interface CustomAxiosRequestConfig extends InternalAxiosRequestConfig {
  _retry?: boolean
}

const instance: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8000',
  headers: { 'Content-Type': 'application/json' },
})

const getAccessToken = () => useAuthStore().getterAccessToken
const getRefreshToken = () => useAuthStore().getterRefreshToken

const setTokens = (accessToken: string | undefined, refreshToken: string | undefined) => {
  useAuthStore().mutationAccessToken(accessToken)
  useAuthStore().mutationRefreshToken(refreshToken)

  if (accessToken) {
    instance.defaults.headers.Authorization = `Bearer ${accessToken}`
  } else {
    delete instance.defaults.headers.Authorization
  }
}

const refreshToken = async (): Promise<string | null> => {
  const authStore = useAuthStore()

  try {
    const response = await axios.post(`${instance.defaults.baseURL}/api/auth/refresh-token`, {
      refresh_token: getRefreshToken(),
    })

    const newAccessToken = response.data.data.access_token

    setTokens(newAccessToken, response.data.refresh_token)

    return newAccessToken
  } catch (error) {
    console.error('Error refreshing token:', error)
    setTokens(undefined, undefined)
    await authStore.actionLogout()
    router.push({ name: 'Login' })
    return null
  }
}

instance.interceptors.request.use(
  async (config) => {
    const token = getAccessToken()
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error),
)

instance.interceptors.response.use(
  (response: AxiosResponse) => response,
  async (error: AxiosError) => {
    const authStore = useAuthStore()
    const originalRequest = error.config as CustomAxiosRequestConfig

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      const newToken = await refreshToken()

      if (newToken) {
        originalRequest.headers.Authorization = `Bearer ${newToken}`
        return instance(originalRequest)
      }
    }

    if (originalRequest?._retry) {
      await authStore.actionLogout()
      router.push({ name: 'Login' })
    }

    return Promise.reject(error)
  },
)

const internals: Internals = {
  parseResponse: (response: AxiosResponse) => response.data,
  parseError: (error: Error | AxiosError) => {
    if (axios.isAxiosError(error)) {
      if (error.response) {
        return Promise.reject(error.response.data)
      }
      return Promise.reject(new Error('Network Error'))
    }
    return Promise.reject(error)
  },
  httpQuery: (context: BuilderContext) => {
    if (!context.httpQuery) return context.url
    const { httpQuery, url } = context

    const params = Object.entries(httpQuery)
    const query = params.reduce((acc: string, [key, val], index: number) => {
      return queryReduce(acc, val, index, key)
    }, '')
    return `${url}${query}`
  },
}

const builder = (context: BuilderContext) => {
  const url = internals.httpQuery(context)
  return instance
    .request({
      ...context,
      url,
      data: context.payload ? JSON.stringify(context.payload) : undefined,
    })
    .then(internals.parseResponse)
    .catch((error: Error | AxiosError) => internals.parseError(error))
}

const queryReduce = (acc: string, val: any, index: number, key: string) => {
  if (val === undefined || val === null || val === 'null' || val === '') return acc
  if (Array.isArray(val) && !val.length) return acc
  if (typeof val === 'string' && !val) return acc

  let symbol = '?'
  if (acc && index > 0) symbol = '&'

  if (typeof val === 'boolean') {
    let bool = 'true'
    if (!val) bool = 'false'
    acc += `${symbol}${key}=${bool}`
    return acc
  }

  acc += `${symbol}${key}=${val}`
  return acc
}

export { builder }
