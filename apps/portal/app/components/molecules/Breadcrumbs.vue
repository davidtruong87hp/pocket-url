<template>
  <div x-data="{ pageName: `Blank Page`}">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <h2
        class="text-xl font-semibold text-gray-800 dark:text-white/90"
        x-text="pageName"
      ></h2>

      <nav>
        <ol class="flex items-center gap-1.5">
          <li>
            <nuxt-link
              class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
              href="/dashboard"
            >
              Dashboard
              <Icon name="lucide:chevron-right" />
            </nuxt-link>
          </li>
          <li
            class="text-sm text-gray-800 dark:text-white/90"
            x-text="pageName"
          ></li>
        </ol>
      </nav>
    </div>
  </div>
</template>
