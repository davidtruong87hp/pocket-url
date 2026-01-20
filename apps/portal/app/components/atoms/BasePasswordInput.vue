<script setup lang="ts">
interface Props {
  modelValue?: string
  placeholder?: string
  disabled?: boolean
  readonly?: boolean
  required?: boolean
  error?: boolean
  label?: string
  hint?: string
  id?: string
  name?: string
  size?: 'sm' | 'md' | 'lg'
  autocomplete?: string
  showToggle?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showToggle: true,
  size: 'md',
  autocomplete: 'current-password',
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
  blur: [event: FocusEvent]
  focus: [event: FocusEvent]
}>()

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
      :for="id"
      class="mb-1.5 block text-sm font-medium text-gray-800 dark:text-white/90"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <div class="relative">
      <input
        :id="id"
        :type="showPassword ? 'text' : 'password'"
        :name="name"
        :value="modelValue ?? ''"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :required="required"
        :autocomplete="autocomplete"
        :class="[
          'w-full rounded-lg border transition-colors',
          'focus:outline-none focus:ring-2 focus:ring-offset-0',
          'disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-500',
          'dark:bg-gray-900 dark:disabled:bg-gray-800',
          size === 'sm'
            ? 'px-3 py-1.5 text-sm'
            : size === 'lg'
              ? 'px-5 py-3 text-lg'
              : 'px-4 py-2.5 text-base',
          showToggle ? 'pr-12' : '',
          error
            ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20'
            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500/20',
        ]"
        @input="
          $emit('update:modelValue', ($event.target as HTMLInputElement).value)
        "
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
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
      v-if="hint && !error"
      class="mt-1.5 text-sm text-gray-500 dark:text-gray-400"
    >
      {{ hint }}
    </p>

    <!-- Error -->
    <p v-if="error" class="mt-1.5 text-sm text-error-600 dark:text-error-400">
      {{ error }}
    </p>
  </div>
</template>
