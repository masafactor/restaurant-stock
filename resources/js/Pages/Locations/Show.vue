<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  location: Object,
})
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="`ロケーション #${props.location.id}`" />

    <div class="max-w-3xl mx-auto p-6 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">ロケーション詳細</h1>
        <div class="flex gap-2">
          <Link :href="route('locations.index')" class="px-3 py-1 border rounded">一覧</Link>
          <Link
            v-if="props.location.can?.update"
            :href="route('locations.edit', props.location.id)"
            class="px-3 py-1 rounded bg-blue-600 text-white"
          >編集</Link>
          <Link
            v-if="props.location.can?.delete"
            method="delete"
            as="button"
            :href="route('locations.destroy', props.location.id)"
            class="px-3 py-1 rounded bg-red-600 text-white"
            onclick="return confirm('削除しますか？')"
          >削除</Link>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <div class="text-gray-500 text-sm">ID</div>
          <div class="font-mono">{{ props.location.id }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">名前</div>
          <div>{{ props.location.name }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">状態</div>
          <div>
            <span v-if="props.location.is_active" class="text-green-600">有効</span>
            <span v-else class="text-gray-400">無効</span>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
