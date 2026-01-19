<template>
  <div class="flex items-center justify-between">
    <button
      class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
    >
      <Icon name="lucide:move-left" size="1.25rem" />

      <span class="hidden sm:inline"> Previous </span>
    </button>

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

    <button
      class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3 dark:hover:text-gray-200"
    >
      <span class="hidden sm:inline"> Next </span>

      <Icon name="lucide:move-right" size="1.25rem" />
    </button>
  </div>
</template>
