<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  movements: Object,
  items: Array,
  locations: Array,
  filters: Object,
  sum: Number,
  can: Object,
})

const form = useForm({
  item_id: props.filters?.item_id ?? '',
  location_id: props.filters?.location_id ?? '',
  type: props.filters?.type ?? '',
  from: props.filters?.from ?? '',
  to: props.filters?.to ?? '',
})

const search = () => {
  form.get(route('stock-movements.index'), { preserveState: true, replace: true })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="在庫異動一覧" />
    <div class="max-w-7xl mx-auto p-6 space-y-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">在庫異動一覧</h1>
        <Link v-if="can.create" :href="route('stock-movements.create')" class="bg-blue-600 text-white px-4 py-2 rounded">
          新規登録
        </Link>
      </div>

      <form @submit.prevent="search" class="flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-sm text-gray-600">商品</label>
          <select v-model="form.item_id" class="border rounded px-3 py-2 min-w-[220px]">
            <option value="">— すべて —</option>
            <option v-for="it in items" :key="it.id" :value="it.id">{{ it.sku }} — {{ it.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-600">ロケーション</label>
          <select v-model="form.location_id" class="border rounded px-3 py-2 min-w-[180px]">
            <option value="">— すべて —</option>
            <option v-for="loc in locations" :key="loc.id" :value="loc.id">{{ loc.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-600">区分</label>
          <select v-model="form.type" class="border rounded px-3 py-2">
            <option value="">— すべて —</option>
            <option value="receive">入庫</option>
            <option value="waste">廃棄</option>
            <option value="adjust">調整</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-600">From</label>
          <input v-model="form.from" type="date" class="border rounded px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm text-gray-600">To</label>
          <input v-model="form.to" type="date" class="border rounded px-3 py-2" />
        </div>
        <button class="bg-gray-800 text-white px-3 py-2 rounded">検索</button>
      </form>

      <div class="text-sm text-gray-600">合計数量：<span class="font-semibold">{{ sum }}</span></div>

      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-3 py-2 border">異動日</th>
            <th class="px-3 py-2 border">商品</th>
            <th class="px-3 py-2 border">ロケーション</th>
            <th class="px-3 py-2 border">区分</th>
            <th class="px-3 py-2 border text-right">数量</th>
            <th class="px-3 py-2 border text-right">単価</th>
            <th class="px-3 py-2 border">メモ</th>
            <th class="px-3 py-2 border w-24">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in movements.data" :key="m.id" class="hover:bg-gray-50">
            <td class="px-3 py-2 border whitespace-nowrap">{{ m.moved_at }}</td>
            <td class="px-3 py-2 border">{{ m.item?.sku }} — {{ m.item?.name }}</td>
            <td class="px-3 py-2 border">{{ m.location?.name }}</td>
            <td class="px-3 py-2 border">
              <span v-if="m.type==='receive'" class="text-green-700">入庫</span>
              <span v-else-if="m.type==='waste'" class="text-red-700">廃棄</span>
              <span v-else class="text-gray-700">調整</span>
            </td>
            <td class="px-3 py-2 border text-right">
              {{ m.type==='waste' ? '-' : '' }}{{ m.qty }}
            </td>
            <td class="px-3 py-2 border text-right">{{ m.unit_cost ?? '-' }}</td>
            <td class="px-3 py-2 border">{{ m.note }}</td>
            <td class="px-0 py-2 border">
            <Link :href="route('stock-movements.show', m.id)" class="text-gray-700 underline">詳細</Link>
        <Link :href="route('stock-movements.edit', m.id)" class="ml-2 text-blue-600 underline">編集</Link>

                    <Link
                        v-if="m.can.delete"
                as="button"
                method="delete"
                :href="route('stock-movements.destroy', m.id)"
                class="text-red-600 hover:underline"
                onclick="return confirm('削除しますか？')"
              >削除</Link>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="mt-3 flex gap-2" v-if="movements.links?.length > 3">
        <Link
          v-for="l in movements.links"
          :key="l.label"
          :href="l.url || ''"
          v-html="l.label"
          class="px-3 py-1 border rounded"
          :class="{ 'bg-gray-200 text-gray-700': l.active, 'text-gray-400': !l.url }"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
