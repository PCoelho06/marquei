import type { BuilderContext, Internals } from '@/types/builder'
import {
  AxiosError,
  type AxiosInstance,
  type AxiosResponse,
  type InternalAxiosRequestConfig,
} from 'axios'

import axios from 'axios'

import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
import router from '@/router'

interface CustomAxiosRequestConfig extends InternalAxiosRequestConfig {
  _retry?: boolean
}

const instance: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8000',
  headers: { 'Content-Type': 'application/json' },
})

const getRefreshToken = () => localStorage.getItem('refresh_token')
const setTokens = (accessToken: string, refreshToken: string) => {
  localStorage.setItem('refresh_token', refreshToken)
  useUserStore().mutationToken(accessToken)
}

const refreshToken = async (): Promise<string | null> => {
  const userStore = useUserStore()

  try {
    const response = await axios.post(`${instance.defaults.baseURL}/api/user/refresh-token`, {
      refresh_token: getRefreshToken(),
    })

    setTokens(response.data.access_token, response.data.refresh_token)

    return response.data.access_token
  } catch (error) {
    console.error('Error refreshing token:', error)
    userStore.actionLogout()
    router.push({ name: 'signin' })
    return null
  }
}

instance.interceptors.response.use(
  (response: AxiosResponse) => response,
  async (error: AxiosError) => {
    const originalRequest = error.config as CustomAxiosRequestConfig

    if (error.response?.status === 401 && !originalRequest?._retry) {
      originalRequest._retry = true
      const newToken = await refreshToken()

      if (newToken) {
        originalRequest.headers.Authorization = `Bearer ${newToken}`
        return instance(originalRequest)
      }
    }
    return Promise.reject(error)
  },
)

const requestInterceptor = (token: string | undefined) => {
  instance.interceptors.request.use((config) => {
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    } else {
      return Promise.reject(new Error('Token is required'))
    }
    return config
  })
}

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

const builder = (context: BuilderContext, isAuthenticationRequest: boolean = false) => {
  const userStore = useUserStore()
  const { getterToken } = storeToRefs(userStore)

  if (!isAuthenticationRequest) requestInterceptor(getterToken.value)

  return instance
    .request({ ...context, data: context.payload ? JSON.stringify(context.payload) : undefined })
    .then(internals.parseResponse)
    .catch((error: Error | AxiosError) => internals.parseError(error, context))
}

export { builder }
