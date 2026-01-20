<script setup lang="ts">
import { useField } from 'vee-validate'
import type { PasswordInputProps } from '~/types'

const props = withDefaults(defineProps<PasswordInputProps>(), {
  showToggle: true,
  size: 'md',
  autocomplete: 'current-password',
  modelValue: '',
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const {
  value: inputValue,
  errorMessage,
  handleBlur,
  handleChange,
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
  'disabled:bg-gray-100 disabled:cursor-not-allowed',
  'dark:bg-gray-900 dark:disabled:bg-gray-800',
  sizeClasses[props.size],
  props.showToggle ? 'pr-12' : '',
  errorMessage.value
    ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
    : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500/20',
])

// Sync with parent
watch(inputValue, (newValue) => {
  emit('update:modelValue', newValue)
})

watch(
  () => props.modelValue,
  (newValue) => {
    if (newValue !== inputValue.value) {
      inputValue.value = newValue
    }
  }
)

const showPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}
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

    <div class="relative">
      <input
        :id="inputId"
        :type="showPassword ? 'text' : 'password'"
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
      />

      <!-- Toggle visibility button -->
      <button
        v-if="showToggle"
        type="button"
        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
        :aria-label="showPassword ? 'Hide password' : 'Show password'"
        @click="togglePassword"
      >
        <Icon
          :name="showPassword ? 'lucide:eye-off' : 'lucide:eye'"
          size="1.25rem"
        />
      </button>
    </div>

    <!-- Hint -->
    <p
      v-if="hint && !errorMessage"
      class="mt-1.5 text-sm text-gray-500 dark:text-gray-400"
    >
      {{ hint }}
    </p>

    <!-- Error -->
    <p
      v-if="errorMessage"
      class="mt-1.5 text-sm text-error-600 dark:text-error-400"
    >
      {{ errorMessage }}
    </p>
  </div>
</template>
