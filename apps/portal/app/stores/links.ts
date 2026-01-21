import { defineStore } from 'pinia'
import type { Link, LinkFilters } from '~/types'
import { linksApi } from '~/services/api/links'

export const useLinksStore = defineStore('links', () => {
  const links = ref<Link[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    from: 0,
    to: 0,
  })

  const fetchLinks = async (filters?: LinkFilters) => {
    loading.value = true
    error.value = null

    try {
      const { data, meta } = await linksApi.getLinks(filters)

      links.value = data
      pagination.value = meta
    } catch (error: any) {
      error.value = error.message || 'Failed to fetch links'
      console.error('Error fetching links: ', error)
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    links,
    loading,
    error,
    pagination,

    // Actions
    fetchLinks,
  }
})
