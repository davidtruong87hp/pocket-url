import type {
  CreateLinkDTO,
  Link,
  LinkFilters,
  PaginatedLinksResponse,
  UpdateLinkDTO,
  UpdateLinkResponse,
} from '~/types'

export const linksApi = {
  async getLinks(filters?: LinkFilters): Promise<PaginatedLinksResponse> {
    const { data } = await useSanctumFetch<PaginatedLinksResponse>(
      '/api/links',
      {
        query: filters,
      }
    )

    const links = data.value?.data || []
    const pagination = data.value?.meta || {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
    }

    return { data: links, meta: pagination }
  },

  async getLink(shortUrl: string): Promise<Link | null> {
    const { data } = await useSanctumFetch<{ data: Link }>(
      `/api/links/${shortUrl}`
    )

    return data.value?.data || null
  },

  async createLink(payload: CreateLinkDTO): Promise<Link | undefined> {
    const { data, error } = await useSanctumFetch<{ data: Link }>(
      '/api/links',
      {
        method: 'POST',
        body: payload,
      }
    )

    if (error.value) throw error.value

    return data.value?.data
  },

  async updateLink(
    shortUrl: string,
    payload: UpdateLinkDTO
  ): Promise<UpdateLinkResponse> {
    const { data, error } = await useSanctumFetch<UpdateLinkResponse>(
      `/api/links/${shortUrl}`,
      {
        method: 'PUT',
        body: payload,
      }
    )

    if (error.value) throw error.value

    return {
      data: data.value?.data,
      message: data.value?.message,
      changed_fields: data.value?.changed_fields,
    }
  },

  async deleteLink(shortUrl: string): Promise<void> {
    const { error } = await useSanctumFetch(`/api/links/${shortUrl}`, {
      method: 'DELETE',
    })

    if (error.value) throw error.value
  },
}
