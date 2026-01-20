<script setup lang="ts">
const { user, logout } = useAuth()

const dropdownOpen = ref(false)

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const handleLogout = async () => {
  await logout()
}
</script>

<template>
  <div class="relative">
    <a
      class="flex items-center text-gray-700 dark:text-gray-400"
      href="#"
      @click.prevent="toggleDropdown"
    >
      <span class="mr-3 h-11 w-11 overflow-hidden rounded-full">
        <nuxt-img src="/user/owner.jpg" alt="User" />
      </span>

      <Icon v-if="dropdownOpen" name="lucide:chevron-up" />
      <Icon v-else name="lucide:chevron-down" />
    </a>

    <div
      v-if="dropdownOpen"
      class="shadow-theme-lg dark:bg-gray-dark absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800"
    >
      <div>
        <span
          class="text-theme-sm block font-medium text-gray-700 dark:text-gray-400"
        >
          {{ user?.name }}
        </span>
        <span
          class="text-theme-xs mt-0.5 block text-gray-500 dark:text-gray-400"
        >
          {{ user?.email }}
        </span>
      </div>

      <ul
        class="flex flex-col gap-1 border-b border-gray-200 pt-4 pb-3 dark:border-gray-800"
      >
        <li>
          <a
            href="profile.html"
            class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
          >
            <Icon
              name="lucide:user"
              size="1.5rem"
              class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
            />
            Edit profile
          </a>
        </li>
        <li>
          <a
            href="settings.html"
            class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
          >
            <Icon
              name="lucide:badge-info"
              size="1.5rem"
              class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
            />
            Support
          </a>
        </li>
      </ul>
      <button
        class="group text-theme-sm mt-3 flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        @click="handleLogout"
      >
        <Icon
          name="lucide:log-out"
          size="1.5rem"
          class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
        />
        Sign out
      </button>
    </div>
  </div>
</template>
