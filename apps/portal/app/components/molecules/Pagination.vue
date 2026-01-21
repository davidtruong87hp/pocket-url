<script setup lang="ts">
interface Props {
  currentPage: number
  totalPages: number
  maxVisible?: number
}

const props = withDefaults(defineProps<Props>(), {
  currentPage: 1,
  maxVisible: 5,
})

const emit = defineEmits(['change'])

const changePage = (page: number) => {
  if (page < 1 || page > props.totalPages || page === props.currentPage) {
    return
  }

  emit('change', page)
}

const visiblePages = computed(() => {
  const pages = []
  const halfVisible = Math.floor(props.maxVisible / 2)

  let startPage = Math.max(2, props.currentPage - halfVisible)
  let endPage = Math.min(props.totalPages - 1, props.currentPage + halfVisible)

  if (props.currentPage <= halfVisible) {
    endPage = Math.min(props.maxVisible, props.totalPages - 1)
  }

  if (props.currentPage >= props.totalPages - halfVisible) {
    startPage = Math.max(2, props.totalPages - props.maxVisible)
  }

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i)
  }

  return pages
})

const showFirstPage = computed(() => {
  return props.totalPages > 1 && !visiblePages.value.includes(1)
})

const showLastPage = computed(() => {
  return props.totalPages > 1 && !visiblePages.value.includes(props.totalPages)
})

const showLeftEllipsis = computed(() => {
  const firstVisiblePage = visiblePages.value[0] || 1
  return firstVisiblePage > 2
})

const showRightEllipsis = computed(() => {
  const lastVisiblePage = visiblePages.value[visiblePages.value.length - 1] || 1
  return lastVisiblePage < props.totalPages - 1
})

const btnClasses = [
  'text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400',
]
</script>

<template>
  <div class="flex items-center justify-between">
    <base-button
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
    >
      <Icon name="lucide:move-left" size="1.25rem" />
      <span class="hidden sm:inline"> Previous </span>
    </base-button>

    <span
      class="block text-sm font-medium text-gray-700 sm:hidden dark:text-gray-400"
    >
      Page {{ currentPage }} of {{ totalPages }}
    </span>

    <div class="flex space-x-3">
      <!-- First Page -->
      <button
        v-if="showFirstPage"
        @click="changePage(1)"
        :class="[
          btnClasses,
          {
            'bg-brand-500 text-white hover:bg-brand-600': currentPage === 1,
          },
        ]"
      >
        1
      </button>

      <!-- Left Ellipsis -->
      <span v-if="showLeftEllipsis" class="px-2">...</span>

      <ul class="hidden items-center gap-0.5 sm:flex">
        <li v-for="page in visiblePages" :key="page">
          <button
            @click="changePage(page)"
            :class="[
              btnClasses,
              {
                'bg-brand-500 text-white hover:bg-brand-600':
                  currentPage === page,
              },
            ]"
          >
            {{ page }}
          </button>
        </li>
      </ul>

      <!-- Right Ellipsis -->
      <span v-if="showRightEllipsis" class="px-2">...</span>

      <!-- Last Page -->
      <button
        v-if="showLastPage"
        @click="changePage(totalPages)"
        :class="[
          btnClasses,
          {
            'bg-brand-500 text-white hover:bg-brand-600':
              currentPage === totalPages,
          },
        ]"
      >
        {{ totalPages }}
      </button>
    </div>

    <base-button
      :disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
    >
      <span class="hidden sm:inline"> Next </span>
      <Icon name="lucide:move-right" size="1.25rem" />
    </base-button>
  </div>
</template>
