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

const setTokens = (accessToken: string, refreshToken: string) => {
  useAuthStore().mutationAccessToken(accessToken)
  useAuthStore().mutationRefreshToken(refreshToken)

  instance.defaults.headers.Authorization = `Bearer ${accessToken}`
}

const refreshToken = async (): Promise<string | null> => {
  const authStore = useAuthStore()

  try {
    const response = await axios.post(`${instance.defaults.baseURL}/api/auth/refresh-token`, {
      refresh_token: getRefreshToken(),
    })

    console.log('🚀 ~ refreshToken ~ response:', response)
    const newAccessToken = response.data.access_token
    console.log('🚀 ~ refreshToken ~ newAccessToken:', newAccessToken)
    console.log('🚀 ~ refreshToken ~ response.data.refresh_token:', response.data.refresh_token)
    setTokens(newAccessToken, response.data.refresh_token)

    return newAccessToken
  } catch (error) {
    console.error('Error refreshing token:', error)
    await authStore.actionLogout()
    router.push({ name: 'Signin' })
    return null
  }
}

instance.interceptors.request.use(
  async (config) => {
    const token = getAccessToken()
    console.log('🚀 ~ token:', token)
    if (token) {
      console.log('🚀 ~ token:', token)
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
      router.push({ name: 'Signin' })
    }

    return Promise.reject(error)
  },
)

const internals: Internals = {
  parseResponse: (response: AxiosResponse) => response.data,
  parseError: (error: Error | AxiosError, context: BuilderContext) => {
    if (axios.isAxiosError(error)) {
      if (error.response) {
        return Promise.reject(error.response.data)
      } else {
        return Promise.reject(new Error('Network Error'))
      }
    } else {
      return Promise.reject(error)
    }
  },
}

const builder = (context: BuilderContext) => {
  return instance
    .request({ ...context, data: context.payload ? JSON.stringify(context.payload) : undefined })
    .then(internals.parseResponse)
    .catch((error: Error | AxiosError) => internals.parseError(error, context))
}

export { builder }
