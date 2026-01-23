<script setup lang="ts">
import type { TimelineStat } from '~/types'

interface Props {
  title: string
  data: TimelineStat[]
}
defineProps<Props>()
</script>

<template>
  <ChartCard :title="title">
    <LineChart :data="data" />
  </ChartCard>
</template>
