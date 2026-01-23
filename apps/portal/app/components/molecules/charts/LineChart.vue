<script setup>
import VueApexCharts from 'vue3-apexcharts'

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
  color: {
    type: String,
    default: '#14b8a6',
  },
  height: {
    type: [String, Number],
    default: 300,
  },
})

const series = computed(() => [
  {
    name: 'Engagements',
    data: props.data.map((d) => d.engagements || 0),
  },
])

const chartOptions = computed(() => ({
  chart: {
    type: 'area',
    toolbar: {
      show: false,
    },
  },
  colors: [props.color],
  fill: {
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.4,
      opacityTo: 0.1,
      stops: [0, 90, 100],
    },
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: 'smooth',
    width: 3,
  },
  xaxis: {
    categories: props.data.map((d) => d.label),
    labels: {
      style: {
        colors: '#64748b',
        fontSize: '12px',
      },
    },
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
  },
  yaxis: {
    labels: {
      style: {
        colors: '#64748b',
        fontSize: '12px',
      },
      formatter: (val) => Math.round(val),
    },
  },
  grid: {
    borderColor: '#f1f5f9',
    strokeDashArray: 4,
    xaxis: {
      lines: {
        show: false,
      },
    },
  },
  tooltip: {
    theme: 'light',
    x: {
      show: true,
    },
    y: {
      formatter: (val) => val.toLocaleString() + ' clicks',
    },
  },
}))
</script>

<template>
  <VueApexCharts
    type="area"
    :height="height"
    :options="chartOptions"
    :series="series"
  />
</template>
