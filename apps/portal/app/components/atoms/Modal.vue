<script setup lang="ts">
interface Props {
  fullScreenBackdrop?: boolean
}

defineProps<Props>()

defineEmits(['close'])
</script>

<template>
  <div
    class="fixed inset-0 flex items-center justify-center overflow-auto z-99999"
  >
    <div
      v-if="fullScreenBackdrop"
      class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
      aria-hidden="true"
      @click="$emit('close')"
    ></div>
    <slot name="body" />
  </div>
</template>
