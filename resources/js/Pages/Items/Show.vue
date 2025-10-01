<script setup>
import { Head, Link } from '@inertiajs/vue3'
const props = defineProps({ item: Object })
</script>

<template>
  <Head :title="`商品詳細 #${props.item.id}`" />
  <div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">商品詳細</h1>
      <div class="flex gap-2">
        <Link :href="route('items.index')" class="border px-3 py-1 rounded">一覧へ</Link>
        <Link v-if="props.item.can.update" :href="route('items.edit', props.item.id)"
              class="bg-blue-600 text-white px-3 py-1 rounded">編集</Link>
        <Link v-if="props.item.can.delete" as="button" method="delete"
              :href="route('items.destroy', props.item.id)"
              class="bg-red-600 text-white px-3 py-1 rounded"
              onclick="return confirm('本当に削除しますか？')">削除</Link>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div><div class="text-gray-500 text-sm">ID</div><div class="font-mono">{{ props.item.id }}</div></div>
      <div><div class="text-gray-500 text-sm">SKU</div><div>{{ props.item.sku }}</div></div>
      <div><div class="text-gray-500 text-sm">名前</div><div>{{ props.item.name }}</div></div>
      <div><div class="text-gray-500 text-sm">カテゴリー</div><div>{{ props.item.category?.name ?? '-' }}</div></div>
      <div><div class="text-gray-500 text-sm">標準原価</div><div>{{ props.item.standard_cost }}</div></div>
      <div><div class="text-gray-500 text-sm">単位</div><div>{{ props.item.unit || '-' }}</div></div>
      <div><div class="text-gray-500 text-sm">有効</div><div>{{ props.item.is_active ? 'はい' : 'いいえ' }}</div></div>
    </div>
  </div>
</template>
