<script setup lang="ts">
const sidebarToggle = ref(false)
const menuToggle = ref(false)
const darkMode = ref(false)
const dropdownOpen = ref(false)
const notifying = ref(true)
</script>

<template>
  <header
    class="sticky top-0 z-99999 flex w-full border-gray-200 bg-white lg:border-b dark:border-gray-800 dark:bg-gray-900"
  >
    <div
      class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6"
    >
      <div
        class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4 dark:border-gray-800"
      >
        <!-- Hamburger Toggle BTN -->
        <button
          :class="
            sidebarToggle
              ? 'lg:bg-transparent dark:lg:bg-transparent bg-gray-100 dark:bg-gray-800'
              : ''
          "
          class="z-99999 flex h-10 w-10 items-center justify-center rounded-lg border-gray-200 text-gray-500 lg:h-11 lg:w-11 lg:border dark:border-gray-800 dark:text-gray-400"
          @click.stop="sidebarToggle = !sidebarToggle"
        >
          <Icon
            name="lucide:text-align-start"
            :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
          />
        </button>
        <!-- Hamburger Toggle BTN -->

        <nuxt-link href="/dashboard" class="lg:hidden">
          <base-logo class="h-18" />
        </nuxt-link>

        <!-- Application nav menu button -->
        <base-button
          variant="ghost"
          class="z-99999 text-gray-700 hover:bg-gray-100 lg:hidden dark:text-gray-400 dark:hover:bg-gray-800"
          :class="menuToggle ? 'bg-gray-100 dark:bg-gray-800' : ''"
          @click="menuToggle = !menuToggle"
        >
          <Icon name="lucide:menu" />
        </base-button>
        <!-- Application nav menu button -->
      </div>

      <div
        :class="menuToggle ? 'flex' : 'hidden'"
        class="shadow-theme-md w-full items-center justify-between gap-4 px-5 py-4 lg:flex lg:justify-end lg:px-0 lg:shadow-none"
      >
        <div class="2xsm:gap-3 flex items-center gap-2">
          <!-- Dark Mode Toggler -->
          <theme-toggle />
          <!-- Dark Mode Toggler -->

          <!-- Notification Menu Area -->
          <div class="relative" @click.outside="dropdownOpen = false">
            <button
              class="hover:text-dark-900 relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
            >
              <span
                :class="!notifying ? 'hidden' : 'flex'"
                class="absolute top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-orange-400"
              >
                <span
                  class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"
                ></span>
              </span>
              <Icon name="lucide:bell" />
            </button>

            <!-- Dropdown Start -->
            <div
              :class="{ hidden: !dropdownOpen }"
              class="shadow-theme-lg dark:bg-gray-dark absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 sm:w-[361px] lg:right-0 dark:border-gray-800"
            >
              <div
                class="mb-3 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800"
              >
                <h5
                  class="text-lg font-semibold text-gray-800 dark:text-white/90"
                >
                  Notification
                </h5>

                <base-button variant="ghost" @click="dropdownOpen = false">
                  <Icon name="lucide:x" />
                </base-button>
              </div>

              <a
                href="#"
                class="text-theme-sm shadow-theme-xs mt-3 flex justify-center rounded-lg border border-gray-300 bg-white p-3 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
              >
                View All Notification
              </a>
            </div>
            <!-- Dropdown End -->
          </div>
          <!-- Notification Menu Area -->
        </div>

        <!-- User Area -->
        <div
          class="relative"
          x-data="{ dropdownOpen: false }"
          @click.outside="dropdownOpen = false"
        >
          <a
            class="flex items-center text-gray-700 dark:text-gray-400"
            href="#"
            @click.prevent="dropdownOpen = !dropdownOpen"
          >
            <span class="mr-3 h-11 w-11 overflow-hidden rounded-full">
              <nuxt-img src="/user/owner.jpg" alt="User" />
            </span>

            <span class="text-theme-sm mr-1 block font-medium"> Musharof </span>

            <Icon v-if="dropdownOpen" name="lucide:chevron-up" />
            <Icon v-else name="lucide:chevron-down" />
          </a>

          <!-- Dropdown Start -->
          <div
            class="shadow-theme-lg dark:bg-gray-dark absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800"
            :class="{ hidden: !dropdownOpen }"
          >
            <div>
              <span
                class="text-theme-sm block font-medium text-gray-700 dark:text-gray-400"
              >
                Musharof Chowdhury
              </span>
              <span
                class="text-theme-xs mt-0.5 block text-gray-500 dark:text-gray-400"
              >
                randomuser@pimjo.com
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
                  href="messages.html"
                  class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                >
                  <Icon
                    name="lucide:settings"
                    size="1.5rem"
                    class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
                  />
                  Account settings
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
            >
              <Icon
                name="lucide:log-out"
                size="1.5rem"
                class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
              />
              Sign out
            </button>
          </div>
          <!-- Dropdown End -->
        </div>
        <!-- User Area -->
      </div>
    </div>
  </header>
</template>
