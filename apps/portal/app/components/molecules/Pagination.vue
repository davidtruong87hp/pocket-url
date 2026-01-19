<template>
  <div class="flex items-center justify-between">
    <base-button variant="outline">
      <Icon name="lucide:move-left" size="1.25rem" />
      <span class="hidden sm:inline"> Previous </span>
    </base-button>

    <span
      class="block text-sm font-medium text-gray-700 sm:hidden dark:text-gray-400"
    >
      Page 1 of 10
    </span>

    <ul class="hidden items-center gap-0.5 sm:flex">
      <li>
        <a
          href="#"
          class="bg-brand-500/[0.08] text-theme-sm text-brand-500 hover:bg-brand-500/[0.08] hover:text-brand-500 dark:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium"
        >
          1
        </a>
      </li>

      <li>
        <a
          href="#"
          class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400"
        >
          2
        </a>
      </li>

      <li>
        <a
          href="#"
          class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400"
        >
          3
        </a>
      </li>

      <li>
        <a
          href="#"
          class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400"
        >
          ...
        </a>
      </li>

      <li>
        <a
          href="#"
          class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400"
        >
          8
        </a>
      </li>

      <li>
        <a
          href="#"
          class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400"
        >
          9
        </a>
      </li>

      <li>
        <a
          href="#"
          class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400"
        >
          10
        </a>
      </li>
    </ul>

    <base-button variant="outline">
      <span class="hidden sm:inline"> Next </span>
      <Icon name="lucide:move-right" size="1.25rem" />
    </base-button>
  </div>
</template>
