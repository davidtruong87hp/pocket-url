<script setup lang="ts"></script>

<template>
  <div class="flex h-screen overflow-hidden">
    <!-- ===== Sidebar Start ===== -->
    <app-sidebar />
    <!-- ===== Sidebar End ===== -->

    <!-- ===== Content Area Start ===== -->
    <div
      class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden"
    >
      <!-- ===== Header Start ===== -->
      <app-header />
      <!-- ===== Header End ===== -->

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
          <!-- Breadcrumb Start -->
          <breadcrumbs />
          <!-- Breadcrumb End -->

          <div
            class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12"
          >
            <slot />
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
  </div>
</template>
