<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  category: Object,
})
</script>

<template>
  <Head :title="`カテゴリー #${props.category.id}`" />

  <div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">カテゴリー詳細</h1>
      <div class="flex gap-2">
        <Link :href="route('categories.index')" class="px-3 py-1 border rounded">一覧</Link>
        <Link
          v-if="props.category.can.update"
          :href="route('categories.edit', props.category.id)"
          class="px-3 py-1 rounded bg-blue-600 text-white"
        >編集</Link>
        <Link
          v-if="props.category.can.delete"
          method="delete"
          as="button"
          :href="route('categories.destroy', props.category.id)"
          class="px-3 py-1 rounded bg-red-600 text-white"
          onclick="return confirm('削除しますか？')"
        >削除</Link>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <div class="text-gray-500 text-sm">ID</div>
        <div class="font-mono">{{ props.category.id }}</div>
      </div>

      <div>
        <div class="text-gray-500 text-sm">名前</div>
        <div>{{ props.category.name }}</div>
      </div>

      <div>
        <div class="text-gray-500 text-sm">状態</div>
        <div>
          <span v-if="props.category.is_active" class="text-green-600">有効</span>
          <span v-else class="text-gray-400">無効</span>
        </div>
      </div>
    </div>
  </div>
</template>
