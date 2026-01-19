<script setup lang="ts">
const sidebarToggle = ref(false)
const page = ref('links')
const selected = ref('Links')
</script>

<template>
  <aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
  >
    <!-- SIDEBAR HEADER -->
    <div
      :class="sidebarToggle ? 'justify-center' : 'justify-between'"
      class="flex items-center gap-2 pt-8 sidebar-header pb-7"
    >
      <nuxt-link href="/dashboard">
        <base-logo class="h-18" />
      </nuxt-link>
    </div>
    <!-- SIDEBAR HEADER -->

    <div
      class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar"
    >
      <!-- Sidebar Menu -->
      <nav x-data="{selected: $persist('Dashboard')}">
        <!-- Menu Group -->
        <div>
          <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
            <span
              class="menu-group-title"
              :class="sidebarToggle ? 'lg:hidden' : ''"
            >
              MENU
            </span>

            <Icon
              v-if="sidebarToggle"
              name="lucide:ellipsis"
              size="1.5rem"
              :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
              class="mx-auto menu-group-icon"
            />
          </h3>

          <ul class="flex flex-col gap-4 mb-6">
            <li>
              <nuxt-link
                href="/dashboard"
                @click.prevent="
                  selected = selected === 'Dashboard' ? '' : 'Dashboard'
                "
                class="menu-item group"
                :class="
                  selected === 'Dashboard'
                    ? 'menu-item-active'
                    : 'menu-item-inactive'
                "
              >
                <Icon
                  name="lucide:layout-dashboard"
                  class="menu-item-icon"
                  :class="
                    selected === 'Dashboard'
                      ? 'menu-item-icon-active'
                      : 'menu-item-icon-inactive'
                  "
                />
                <span
                  class="menu-item-text"
                  :class="sidebarToggle ? 'lg:hidden' : ''"
                >
                  Dashboard
                </span>
              </nuxt-link>
            </li>

            <li>
              <nuxt-link
                href="/links"
                @click="selected = selected === 'Links' ? '' : 'Links'"
                class="menu-item group"
                :class="
                  selected === 'Links' && page === 'links'
                    ? 'menu-item-active'
                    : 'menu-item-inactive'
                "
              >
                <Icon
                  name="lucide:link"
                  class="menu-item-icon"
                  :class="
                    selected === 'Links' && page === 'links'
                      ? 'menu-item-icon-active'
                      : 'menu-item-icon-inactive'
                  "
                />

                <span
                  class="menu-item-text"
                  :class="sidebarToggle ? 'lg:hidden' : ''"
                >
                  Links
                </span>
              </nuxt-link>
            </li>

            <li>
              <nuxt-link
                href="/analytics"
                @click="selected = selected === 'Analytics' ? '' : 'Analytics'"
                class="menu-item group"
                :class="
                  selected === 'Analytics' && page === 'analytics'
                    ? 'menu-item-active'
                    : 'menu-item-inactive'
                "
              >
                <Icon
                  name="lucide:chart-no-axes-combined"
                  class="menu-item-icon"
                  :class="
                    selected === 'Analytics' && page === 'analytics'
                      ? 'menu-item-icon-active'
                      : 'menu-item-icon-inactive'
                  "
                />

                <span
                  class="menu-item-text"
                  :class="sidebarToggle ? 'lg:hidden' : ''"
                >
                  Analytics
                </span>
              </nuxt-link>
            </li>
          </ul>
        </div>
      </nav>
      <!-- Sidebar Menu -->
    </div>
  </aside>
</template>
