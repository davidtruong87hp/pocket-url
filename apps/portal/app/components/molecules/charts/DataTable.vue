<script setup>
defineProps({
  data: {
    type: Array,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-gray-200">
          <th
            v-for="(col, idx) in columns"
            :key="idx"
            class="text-left py-3 px-4 text-sm font-semibold text-gray-700"
          >
            {{ col }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(row, idx) in data"
          :key="idx"
          class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
        >
          <td
            v-for="(cell, cellIdx) in row"
            :key="cellIdx"
            class="py-3 px-4 text-sm text-gray-900"
          >
            {{ cell }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
