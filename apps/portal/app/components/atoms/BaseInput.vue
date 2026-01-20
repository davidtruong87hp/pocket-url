<script setup lang="ts">
interface Props {
  modelValue?: string | number
  type?: 'text' | 'password' | 'email' | 'number' | 'tel' | 'url' | 'search'
  placeholder?: string
  disabled?: boolean
  readonly?: boolean
  required?: boolean
  error?: string
  label?: string
  hint?: string
  id?: string
  name?: string
  autocomplete?: string
  size?: 'sm' | 'md' | 'lg'
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  size: 'md',
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
  blur: [event: FocusEvent]
  focus: [event: FocusEvent]
  input: [event: Event]
}>()

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
  props.error
    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
    : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500/20',
  props.class,
])
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
      :value="modelValue ?? ''"
      :placeholder="placeholder"
      :disabled="disabled"
      :readonly="readonly"
      :required="required"
      :autocomplete="autocomplete"
      :class="inputClasses"
      @input="
        $emit('update:modelValue', ($event.target as HTMLInputElement).value)
      "
      @blur="$emit('blur', $event)"
      @focus="$emit('focus', $event)"
    />

    <!-- Hint text -->
    <p
      v-if="hint && !error"
      class="mt-1.5 text-sm text-gray-500 dark:text-gray-400"
    >
      {{ hint }}
    </p>

    <!-- Error message -->
    <p v-if="error" class="mt-1.5 text-sm text-error-600 dark:text-error-400">
      {{ error }}
    </p>
  </div>
</template>
