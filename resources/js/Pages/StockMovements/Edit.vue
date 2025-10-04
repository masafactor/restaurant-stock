<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  movement: Object,   // { id, item_id, location_id, type, qty, unit_cost, moved_at, note }
  items: Array,
  locations: Array,
})

const form = useForm({
  item_id: props.movement.item_id ?? null,
  location_id: props.movement.location_id ?? null,
  type: props.movement.type ?? 'receive',
  qty: props.movement.qty ?? '',
  unit_cost: props.movement.unit_cost ?? '',
  moved_at: props.movement.moved_at ?? new Date().toISOString().slice(0,10),
  note: props.movement.note ?? '',
})

const submit = () => {
  form.put(route('stock-movements.update', props.movement.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="在庫異動 編集" />

    <div class="max-w-3xl mx-auto p-6 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">在庫異動 編集</h1>
        <Link :href="route('stock-movements.index')" class="text-gray-600 hover:underline">一覧へ戻る</Link>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <div>
          <label class="block text-sm font-medium mb-1">商品 <span class="text-red-500">*</span></label>
          <select v-model.number="form.item_id" class="w-full border rounded px-3 py-2">
            <option :value="null">— 選択 —</option>
            <option v-for="it in props.items" :key="it.id" :value="it.id">
              {{ it.sku }} — {{ it.name }}
            </option>
          </select>
          <div v-if="form.errors.item_id" class="text-red-600 text-sm mt-1">{{ form.errors.item_id }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">ロケーション <span class="text-red-500">*</span></label>
          <select v-model.number="form.location_id" class="w-full border rounded px-3 py-2">
            <option :value="null">— 選択 —</option>
            <option v-for="loc in props.locations" :key="loc.id" :value="loc.id">{{ loc.name }}</option>
          </select>
          <div v-if="form.errors.location_id" class="text-red-600 text-sm mt-1">{{ form.errors.location_id }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">区分 <span class="text-red-500">*</span></label>
          <select v-model="form.type" class="w-full border rounded px-3 py-2">
            <option value="receive">入庫</option>
            <option value="waste">廃棄</option>
            <option value="adjust">調整</option>
          </select>
          <div v-if="form.errors.type" class="text-red-600 text-sm mt-1">{{ form.errors.type }}</div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">数量 <span class="text-red-500">*</span></label>
            <input v-model.number="form.qty" type="number" step="0.001" min="0.001" class="w-full border rounded px-3 py-2" />
            <div v-if="form.errors.qty" class="text-red-600 text-sm mt-1">{{ form.errors.qty }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">単価</label>
            <input v-model.number="form.unit_cost" type="number" step="0.0001" min="0" class="w-full border rounded px-3 py-2" />
            <div v-if="form.errors.unit_cost" class="text-red-600 text-sm mt-1">{{ form.errors.unit_cost }}</div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">異動日 <span class="text-red-500">*</span></label>
          <input v-model="form.moved_at" type="date" class="w-full border rounded px-3 py-2" />
          <div v-if="form.errors.moved_at" class="text-red-600 text-sm mt-1">{{ form.errors.moved_at }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">メモ</label>
          <textarea v-model="form.note" rows="3" class="w-full border rounded px-3 py-2"></textarea>
          <div v-if="form.errors.note" class="text-red-600 text-sm mt-1">{{ form.errors.note }}</div>
        </div>

        <div class="flex gap-3">
          <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-4 py-2 rounded">
            更新する
          </button>
          <Link :href="route('stock-movements.index')" class="px-4 py-2 border rounded">キャンセル</Link>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
