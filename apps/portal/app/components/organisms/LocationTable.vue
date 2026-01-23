<script setup lang="ts">
import type { LocationBreakdown } from '~/types'

interface Props {
  data: LocationBreakdown[]
  title: string
  columns: string[]
}

defineProps<Props>()
</script>

<template>
  <ChartCard :title="title">
    <DataTable :data="data" :columns="columns" />
  </ChartCard>
</template>
