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
      <main class="h-screen dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
          <!-- Breadcrumb Start -->
          <breadcrumbs />
          <!-- Breadcrumb End -->

          <slot />
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
  </div>
</template>
