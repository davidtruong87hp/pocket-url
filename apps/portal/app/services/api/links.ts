import type { LinkFilters, PaginatedLinksResponse } from '~/types'

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
}
