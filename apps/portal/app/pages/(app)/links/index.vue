<script setup lang="ts">
const isEditLinkModal = ref(false)
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3"
  >
    <div class="flex flex-col items-center px-4 py-5 xl:px-6 xl:py-6">
      <div
        class="flex flex-col w-full gap-5 sm:justify-between xl:flex-row xl:items-center"
      >
        <div class="hidden lg:block">
          <form>
            <div class="relative">
              <span class="absolute top-1/2 left-4 -translate-y-1/2">
                <Icon
                  name="lucide:search"
                  size="1rem"
                  class="fill-gray-500 dark:fill-gray-400"
                />
              </span>
              <input
                type="text"
                placeholder="Search links..."
                id="search-input"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pr-14 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[430px] dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30"
              />

              <button
                id="search-button"
                class="absolute top-1/2 right-2.5 inline-flex -translate-y-1/2 items-center gap-0.5 rounded-lg border border-gray-200 bg-gray-50 px-[7px] py-[4.5px] text-xs -tracking-[0.2px] text-gray-500 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400"
              >
                <span> ⌘ </span>
                <span> K </span>
              </button>
            </div>
          </form>
        </div>

        <div class="flex flex-wrap items-center gap-3 xl:justify-end">
          <button
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]"
          >
            <Icon name="lucide:sliders-horizontal" size="1rem" />

            Filter &amp; Sort
          </button>

          <button
            @click="isEditLinkModal = true"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"
          >
            Create Link

            <Icon name="lucide:plus" size="1rem" />
          </button>
        </div>
      </div>
    </div>
    <div
      class="p-4 space-y-8 border-t border-gray-200 mt-7 dark:border-gray-800 sm:mt-0 xl:p-6"
    >
      <links-table />
    </div>

    <link-edit-modal v-if="isEditLinkModal" @close="isEditLinkModal = false" />
  </div>
</template>
