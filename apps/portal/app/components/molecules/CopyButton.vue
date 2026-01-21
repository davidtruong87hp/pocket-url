<script setup lang="ts">
interface Props {
  text: string
  title?: string
  buttonClass?: string
  disabled?: boolean
  resetDelay?: number
  size?: 'sm' | 'md' | 'lg'
  onSuccess?: () => void
  onError?: (error: Error) => void
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Copy to clipboard',
  disabled: false,
  resetDelay: 5000,
  size: 'sm',
  onSuccess: () => {},
  onError: () => {},
})

const iconSizes = {
  sm: '1rem',
  md: '1.25rem',
  lg: '1.5rem',
}

const copied = ref(false)
let timeoutId: NodeJS.Timeout | null = null

const handleCopy = async () => {
  try {
    await navigator.clipboard.writeText(props.text)
    copied.value = true

    if (props.onSuccess) {
      props.onSuccess()
    }

    if (timeoutId) {
      clearTimeout(timeoutId)
    }

    timeoutId = setTimeout(() => {
      copied.value = false
      timeoutId = null
    }, props.resetDelay)
  } catch (error) {
    console.error('Failed to copy text:', error)

    if (props.onError) {
      props.onError(error as Error)
    }
  }
}

onBeforeUnmount(() => {
  if (timeoutId) {
    clearTimeout(timeoutId)
  }
})
</script>

<template>
  <base-button variant="ghost" :title="title" @click="handleCopy">
    <span v-if="!copied">
      <slot name="icon">
        <Icon name="lucide:copy" :size="iconSizes[size]" />
      </slot>
    </span>
    <span v-else>
      <slot name="success-icon">
        <Icon
          name="lucide:copy-check"
          :size="iconSizes[size]"
          class="text-green-500"
        />
      </slot>
    </span>
  </base-button>
</template>
