export interface Link {
  id: number
  shortCode: string
  shortDomain: string
  originalUrl: string
  createdAt: string
  title?: string
  favicon?: string
}

export interface CreateLinkDTO {
  title?: string
  originalUrl: string
}

export interface UpdateLinkDTO {
  title?: string
  originalUrl?: string
}
