import type { BaseDeletePayload, BaseGetPayload, BaseQuery } from '@/types'

export interface ResourceExceptionGetPayload extends BaseGetPayload {
  resourceId: number
}

export interface ResourceExceptionDeletePayload extends BaseDeletePayload {
  resourceId: number
}

export interface ResourceExceptionCreatePayload {
  date: string
  startTime: string
  endTime: string
  resourceId: number
}

export interface ResourceException extends ResourceExceptionCreatePayload {
  id: number
  createdAt: string
  updatedAt: string
}

export interface ResourceExceptionUpdatePayload extends ResourceExceptionCreatePayload {
  id: number
}

export interface ResourceExceptionListPayload {
  resourceId: number
  httpQuery: object
}

export interface ResourceExceptionQuery extends BaseQuery {
  date?: string
  startTime?: string
  endTime?: string
}
