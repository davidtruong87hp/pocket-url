import { defineStore } from 'pinia'
import type {
  CreateLinkDTO,
  Link,
  LinkFilters,
  LinkStats,
  UpdateLinkDTO,
  UpdateLinkResponse,
} from '~/types'
import { linksApi } from '~/services/api/links'

export const useLinksStore = defineStore('links', () => {
  const links = ref<Link[]>([])
  const currentLink = ref<Link | null>(null)
  const currentLinkStats = ref<LinkStats | null>(null)
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

  const fetchLink = async (shortUrl: string) => {
    loading.value = true
    error.value = null

    try {
      const response = await linksApi.getLink(shortUrl)
      currentLink.value = response?.link || null
      currentLinkStats.value = response?.stats || null
    } catch (error: any) {
      error.value = error.message || 'Failed to fetch link'
      console.error('Error fetching link: ', error)
    } finally {
      loading.value = false
    }
  }

  const createLink = async (
    payload: CreateLinkDTO,
  ): Promise<Link | undefined> => {
    loading.value = true
    error.value = null

    try {
      const createdLink = await linksApi.createLink(payload)

      // Add to the local list
      if (createdLink) {
        links.value.unshift(createdLink)
        pagination.value.total += 1
      }

      return createdLink
    } catch (error: any) {
      error.value = error.message || 'Failed to create link'

      throw error
    } finally {
      loading.value = false
    }
  }

  const updateLink = async (
    shortUrl: string,
    payload: UpdateLinkDTO,
  ): Promise<UpdateLinkResponse | undefined> => {
    loading.value = true
    error.value = null

    try {
      const response = await linksApi.updateLink(shortUrl, payload)
      const updatedLink = response.data

      // Update in local list
      if (updatedLink) {
        const index = links.value.findIndex(
          (link) => link.id === updatedLink.id,
        )
        if (index !== -1) {
          links.value[index] = updatedLink
        }

        // Update current link if it's the same
        if (currentLink.value?.id === updatedLink.id) {
          currentLink.value = updatedLink
        }
      }

      return response
    } catch (error: any) {
      error.value = error.message || 'Failed to update link'

      throw error
    } finally {
      loading.value = false
    }
  }

  const deleteLink = async (shortUrl: string): Promise<boolean> => {
    loading.value = true
    error.value = null

    try {
      await linksApi.deleteLink(shortUrl)

      // Remove from the local list
      links.value = links.value.filter((link) => link.short_url !== shortUrl)
      pagination.value.total -= 1

      // Clear current link if it's the same
      if (currentLink.value?.short_url === shortUrl) {
        currentLink.value = null
        currentLinkStats.value = null
      }

      return true
    } catch (error: any) {
      error.value = error.message || 'Failed to delete link'
      console.error('Error deleting link: ', error)

      return false
    } finally {
      loading.value = false
    }
  }

  const refresh = async () => {
    if (links.value.length > 0) {
      fetchLinks()
    }
  }

  return {
    // State
    links,
    currentLink,
    currentLinkStats,
    loading,
    error,
    pagination,

    // Actions
    fetchLinks,
    fetchLink,
    createLink,
    updateLink,
    deleteLink,
    refresh,
  }
})
