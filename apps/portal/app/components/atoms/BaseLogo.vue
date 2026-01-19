<script setup lang="ts">
interface Props {
  collapsed?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  collapsed: false,
})

const colorMode = useColorMode()

const isDarkMode = computed(() => colorMode.value === 'dark')

const logoSrc = computed(() => {
  const variant = isDarkMode.value ? 'dark' : 'light'
  const size = props.collapsed ? 'icon' : 'full'

  return `/logo/${size}-${variant}.svg`
})
</script>

<template>
  <ClientOnly>
    <nuxt-img
      :src="logoSrc"
      alt="Logo"
      :class="[collapsed ? 'w-full' : 'h-8', props.class]"
    />
  </ClientOnly>
</template>
