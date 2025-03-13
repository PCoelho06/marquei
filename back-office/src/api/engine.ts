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
    console.log('🚀 ~ refreshToken ~ response:', response)

    const newAccessToken = response.data.data.access_token
    console.log('🚀 ~ refreshToken ~ newAccessToken:', newAccessToken)

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
    console.log('🚀 ~ originalRequest:', originalRequest)

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      console.log('🚀 ~ originalRequest._retry:', originalRequest._retry)

      const newToken = await refreshToken()

      if (newToken) {
        console.log('🚀 ~ newToken:', newToken)
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
}

const builder = (context: BuilderContext) => {
  return instance
    .request({ ...context, data: context.payload ? JSON.stringify(context.payload) : undefined })
    .then(internals.parseResponse)
    .catch((error: Error | AxiosError) => internals.parseError(error))
}

export { builder }
