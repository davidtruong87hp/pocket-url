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
  horizontal: {
    type: Boolean,
    default: false,
  },
})

const series = computed(() => [
  {
    name: 'Clicks',
    data: props.data.map((d) => d.value || 0),
  },
])

const chartOptions = computed(() => ({
  chart: {
    type: 'bar',
    fontFamily: 'inherit',
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      horizontal: props.horizontal,
      borderRadius: 4,
      columnWidth: '60%',
    },
  },
  colors: [props.color],
  dataLabels: {
    enabled: false,
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
    y: {
      formatter: (val) => val.toLocaleString() + ' clicks',
    },
  },
}))
</script>

<template>
  <VueApexCharts
    type="bar"
    :options="chartOptions"
    :series="series"
    :height="height"
  />
</template>
