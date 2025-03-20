export interface BuilderContext {
  url: string
  method: string
  payload: object
  httpQuery?: object
}

export interface Internals {
  parseResponse: (response: any) => any
  parseError: (error: any) => any
  httpQuery: (context: BuilderContext) => string
}
