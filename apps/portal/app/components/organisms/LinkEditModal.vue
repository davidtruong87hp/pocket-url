<script setup lang="ts">
import { object, string } from 'yup'
import type { Link } from '~/types'
import type { SubmissionContext } from 'vee-validate'
import { useLinksStore } from '~/stores/links'

interface Props {
  link?: Link
}

const props = defineProps<Props>()

const emit = defineEmits(['close'])

const { loading, createLink, updateLink } = useLinksStore()
const { handleServerErrors } = useFormServerErrors()

const isEdit = computed(() => !!props.link)

const schema = object().shape({
  original_url: string().required('Original URL is required'),
  title: string().max(255, 'Title is too long (255 characters max)').optional(),
})

const onSubmit = async (values: any, ctx: SubmissionContext) => {
  try {
    const payload = {
      url: values.original_url,
      title: values.title,
    }

    if (props.link === undefined) {
      await createLink(payload)
    } else {
      await updateLink(props.link.short_url, payload)
    }

    useNotification().success({
      title: 'Success',
      message: `Link ${isEdit.value ? 'updated' : 'created'} successfully`,
    })

    emit('close')
  } catch (error: any) {
    const errorMessage = handleServerErrors(error, ctx)

    if (errorMessage) {
      useNotification().error({
        title: 'Error',
        message: errorMessage,
      })
    }
  }
}
</script>

<template>
  <modal full-screen-backdrop>
    <template #body>
      <div
        class="no-scrollbar relative w-full max-w-175 overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-11"
      >
        <div class="px-2">
          <h4
            class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
          >
            {{ link?.id ? 'Edit Link' : 'Create a new link' }}
          </h4>
          <p
            v-if="!link"
            class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7"
          >
            You have 2 links and 3 custom back-halves remaining this month.
          </p>
        </div>
        <!-- close btn -->
        <base-button
          variant="ghost"
          class="z-999 absolute right-5 top-5"
          @click="$emit('close')"
        >
          <Icon name="lucide:x" size="1.25rem" />
        </base-button>

        <VeeForm
          :validation-schema="schema"
          :key="props.link?.id || 'new'"
          @submit="onSubmit"
          class="flex flex-col"
        >
          <div class="overflow-y-auto px-2">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <base-input
                  name="original_url"
                  label="Destination URL"
                  :required="true"
                  :model-value="link?.original_url"
                  placeholder="https://example.com/my-long-url"
                />
              </div>

              <div class="sm:col-span-2">
                <base-input
                  name="title"
                  label="Title (optional)"
                  :model-value="link?.title"
                />
              </div>
            </div>
          </div>
          <div class="flex gap-6 px-2 mt-6 justify-end">
            <div class="flex items-center w-full gap-3 sm:w-auto">
              <base-button @click="$emit('close')" :disabled="loading">
                Cancel
              </base-button>
              <base-button variant="primary" :loading="loading">
                {{ loading ? 'Saving...' : 'Save' }}
              </base-button>
            </div>
          </div>
        </VeeForm>
      </div>
    </template>
  </modal>
</template>
