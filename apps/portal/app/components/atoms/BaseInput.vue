<script setup lang="ts">
import { useField } from 'vee-validate'
import type { BaseInputProps } from '~/types'

const props = withDefaults(defineProps<BaseInputProps>(), {
  type: 'text',
  size: 'md',
  modelValue: '',
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
  blur: [event: FocusEvent]
  focus: [event: FocusEvent]
}>()

const {
  value: inputValue,
  errorMessage,
  handleBlur,
  handleChange,
  meta,
} = useField(() => props.name, props.rules, {
  initialValue: props.modelValue,
  syncVModel: true,
})

const inputId = computed(() => {
  if (props.id) return props.id
  if (props.name) return `input-${props.name}`
  return undefined
})

const sizeClasses = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2.5 text-base',
  lg: 'px-5 py-3 text-lg',
}

const inputClasses = computed(() => [
  'w-full rounded-lg border transition-colors',
  'focus:outline-none focus:ring-2 focus:ring-offset-0',
  'placeholder:text-gray-400 dark:placeholder:text-white/30',
  'disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-500',
  'dark:bg-gray-900 dark:disabled:bg-gray-800',
  sizeClasses[props.size],
  errorMessage.value
    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
    : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500/20',
  props.class,
])

// sync with parent v-model
watch(inputValue, (newValue) => {
  emit('update:modelValue', newValue)
})

// Update from parent
watch(
  () => props.modelValue,
  (newValue) => {
    if (newValue !== inputValue.value) {
      inputValue.value = newValue
    }
  }
)
</script>

<template>
  <div class="w-full">
    <!-- Label -->
    <label
      v-if="label"
      :for="inputId"
      class="mb-1.5 block text-sm font-medium text-gray-800 dark:text-white/90"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <input
      :id="inputId"
      :type="type"
      :name="name"
      :value="inputValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :readonly="readonly"
      :required="required"
      :autocomplete="autocomplete"
      :class="inputClasses"
      @input="handleChange"
      @blur="handleBlur"
      @focus="$emit('focus', $event)"
    />

    <!-- Hint text -->
    <p
      v-if="hint && !errorMessage"
      class="mt-1.5 text-sm text-gray-500 dark:text-gray-400"
    >
      {{ hint }}
    </p>

    <!-- Error message -->
    <p
      v-if="errorMessage"
      class="mt-1.5 text-sm text-error-600 dark:text-error-400"
    >
      {{ errorMessage }}
    </p>
  </div>
</template>
