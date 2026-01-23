<script setup>
import VueApexCharts from 'vue3-apexcharts'

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
  colors: {
    type: Array,
    default: () => ['#14b8a6', '#a5f3fc', '#1e40af', '#bfdbfe', '#fb923c'],
  },
  total: [String, Number],
  centerLabel: {
    type: String,
    default: 'Total',
  },
})

const series = computed(() => props.data.map((d) => d.value || 0))
const labels = computed(() => props.data.map((d) => d.label || ''))

const chartOptions = computed(() => ({
  chart: {
    type: 'donut',
    fontFamily: 'inherit',
  },
  labels: labels.value,
  colors: props.colors,
  legend: {
    show: false,
  },
  dataLabels: {
    enabled: false,
  },
  plotOptions: {
    pie: {
      donut: {
        size: '75%',
        labels: {
          show: true,
          name: {
            show: true,
            fontSize: '14px',
            color: '#64748b',
          },
          value: {
            show: true,
            fontSize: '28px',
            fontWeight: 700,
            color: '#0f172a',
            formatter: (val) => val,
          },
          total: {
            show: true,
            label: props.centerLabel,
            fontSize: '14px',
            color: '#64748b',
            formatter: () =>
              props.total || series.value.reduce((a, b) => a + b, 0),
          },
        },
      },
    },
  },
  tooltip: {
    y: {
      formatter: (val) => val.toLocaleString(),
    },
  },
}))
</script>

<template>
  <div class="flex flex-col lg:flex-row items-center gap-8">
    <div class="w-64 h-64 shrink-0">
      <VueApexCharts
        type="donut"
        :options="chartOptions"
        :series="series"
        height="256"
      />
    </div>
    <div class="flex-1 w-full">
      <div
        v-for="(item, idx) in data"
        :key="idx"
        class="flex items-center justify-between py-2 border-b last:border-b-0"
      >
        <div class="flex items-center gap-3">
          <div
            class="w-3 h-3 rounded-full shrink-0"
            :style="{ backgroundColor: colors[idx] }"
          />
          <span class="text-sm font-medium text-gray-700">{{
            item.label
          }}</span>
        </div>
        <div class="text-right">
          <span class="text-sm font-semibold text-gray-900">{{
            item.value.toLocaleString()
          }}</span>
          <span v-if="item.percentage" class="text-xs text-gray-500 ml-2">
            ({{ item.percentage }}%)
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
