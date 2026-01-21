export interface Link {
  id: number
  short_code: string
  short_url: string
  original_url: string
  title: string
  favicon?: string
  created_at: string
  updated_at: string
}

export interface CreateLinkDTO {
  title?: string
  url: string
}

export interface UpdateLinkDTO {
  title?: string
  url?: string
}

export interface LinkFilters {
  page?: number
  limit?: number
}

export interface PaginatedLinksResponse {
  data: Link[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
  }
}

export interface UpdateLinkResponse {
  data?: Link
  message?: string
  changed_fields?: string[]
}
