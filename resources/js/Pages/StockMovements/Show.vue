<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  movement: Object,
})
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="`在庫異動 #${props.movement.id}`" />

    <div class="max-w-3xl mx-auto p-6 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">在庫異動 詳細</h1>
        <div class="flex gap-2">
          <Link :href="route('stock-movements.index')" class="px-3 py-1 border rounded">一覧</Link>
          <Link
            v-if="props.movement.can?.update"
            :href="route('stock-movements.edit', props.movement.id)"
            class="px-3 py-1 rounded bg-blue-600 text-white"
          >編集</Link>
          <Link
            v-if="props.movement.can?.delete"
            as="button"
            method="delete"
            :href="route('stock-movements.destroy', props.movement.id)"
            class="px-3 py-1 rounded bg-red-600 text-white"
            onclick="return confirm('削除しますか？')"
          >削除</Link>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <div class="text-gray-500 text-sm">ID</div>
          <div class="font-mono">{{ props.movement.id }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">商品</div>
          <div>{{ props.movement.item?.sku }} — {{ props.movement.item?.name }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">ロケーション</div>
          <div>{{ props.movement.location?.name }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">区分</div>
          <div>
            <span v-if="props.movement.type==='receive'">入庫</span>
            <span v-else-if="props.movement.type==='waste'">廃棄</span>
            <span v-else>調整</span>
          </div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">数量</div>
          <div>{{ props.movement.type==='waste' ? '-' : '' }}{{ props.movement.qty }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">単価</div>
          <div>{{ props.movement.unit_cost ?? '-' }}</div>
        </div>

        <div>
          <div class="text-gray-500 text-sm">異動日</div>
          <div>{{ props.movement.moved_at }}</div>
        </div>

        <div class="col-span-2">
          <div class="text-gray-500 text-sm">メモ</div>
          <div class="whitespace-pre-line">{{ props.movement.note || '-' }}</div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
