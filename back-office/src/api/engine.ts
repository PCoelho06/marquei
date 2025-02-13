import type { BuilderContext, Internals } from '@/types/builder'
import { AxiosError, type AxiosInstance, type AxiosResponse } from 'axios'

import axios from 'axios'

import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'

const instance: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8000',
  headers: { 'Content-Type': 'application/json' },
})

instance.interceptors.response.use(
  (response: AxiosResponse) => Promise.resolve(response),
  (error: AxiosError) => {
    return Promise.reject(error)
  },
)

const requestInterceptor = (token: string | undefined) => {
  instance.interceptors.request.clear()
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
