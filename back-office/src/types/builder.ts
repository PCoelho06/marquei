export interface BuilderContext {
  url: string
  method: string
  payload: object
}

export interface Internals {
  parseResponse: (response: any) => any
  parseError: (error: any, context: BuilderContext) => any
}
