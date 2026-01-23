<script setup>
defineProps({
  title: String,
  value: [String, Number],
  subtitle: String,
  trend: Number,
  icon: String,
})
</script>

<template>
  <div
    class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow"
  >
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <p class="text-sm font-medium text-gray-600 mb-1">{{ title }}</p>
        <p class="text-3xl font-bold text-gray-900">{{ value }}</p>
        <p v-if="subtitle" class="text-xs text-gray-500 mt-1">{{ subtitle }}</p>
      </div>
      <div
        v-if="trend !== null && trend !== undefined"
        :class="
          trend >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50'
        "
        class="flex items-center gap-1 px-2 py-1 rounded-full text-sm font-semibold"
      >
        <span>{{ trend >= 0 ? '↑' : '↓' }}</span>
        <span>{{ Math.abs(trend) }}%</span>
      </div>
    </div>
  </div>
</template>
