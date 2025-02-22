import type { BuilderContext, Internals } from '@/types/builder'
import {
  AxiosError,
  type AxiosInstance,
  type AxiosResponse,
  type InternalAxiosRequestConfig,
} from 'axios'

import axios from 'axios'
import { useUserStore } from '@/stores/user'
import router from '@/router'

interface CustomAxiosRequestConfig extends InternalAxiosRequestConfig {
  _retry?: boolean
}

const instance: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8000',
  headers: { 'Content-Type': 'application/json' },
})

// Récupère le token depuis localStorage
const getAccessToken = () => localStorage.getItem('access_token')
const getRefreshToken = () => localStorage.getItem('refresh_token')

const setTokens = (accessToken: string, refreshToken: string) => {
  localStorage.setItem('refresh_token', refreshToken)
  localStorage.setItem('access_token', accessToken)

  // Assigne immédiatement le nouveau token à Axios pour éviter une requête échouée
  instance.defaults.headers.Authorization = `Bearer ${accessToken}`
}

const refreshToken = async (): Promise<string | null> => {
  const userStore = useUserStore()

  try {
    const response = await axios.post(`${instance.defaults.baseURL}/api/user/refresh-token`, {
      refresh_token: getRefreshToken(),
    })

    const newAccessToken = response.data.access_token
    setTokens(newAccessToken, response.data.refresh_token)

    return newAccessToken
  } catch (error) {
    console.error('Error refreshing token:', error)
    userStore.actionLogout()
    router.push({ name: 'signin' })
    return null
  }
}

// ✅ INTERCEPTEUR DE REQUÊTE : Vérifie que le token est toujours à jour AVANT d'envoyer la requête
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

// ✅ INTERCEPTEUR DE RÉPONSE : Gère l'expiration du token et relance la requête avec le bon token
instance.interceptors.response.use(
  (response: AxiosResponse) => response,
  async (error: AxiosError) => {
    const userStore = useUserStore()
    const originalRequest = error.config as CustomAxiosRequestConfig

    // Si 401 et qu'on n'a pas encore tenté de refresh
    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      const newToken = await refreshToken()

      if (newToken) {
        // Assigner le nouveau token immédiatement
        originalRequest.headers.Authorization = `Bearer ${newToken}`
        return instance(originalRequest) // Relance la requête avec le bon token
      }
    }

    if (originalRequest?._retry) {
      userStore.actionLogout()
      router.push({ name: 'signin' })
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

const builder = (context: BuilderContext, isAuthenticationRequest: boolean = false) => {
  return instance
    .request({ ...context, data: context.payload ? JSON.stringify(context.payload) : undefined })
    .then(internals.parseResponse)
    .catch((error: Error | AxiosError) => internals.parseError(error, context))
}

export { builder }
