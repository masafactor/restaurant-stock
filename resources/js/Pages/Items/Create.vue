<!-- resources/js/Pages/Items/Create.vue -->
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
const props = defineProps({ categories: Array })

const form = useForm({
  sku: '',
  name: '',
  category_id: null,
  standard_cost: '',
  unit: '',          // ← 追加
  is_active: true,   // ← 追加（初期ONなら true / OFFなら false）
})

const submit = () => {
  form.post(route('items.store'))
}
</script>

<template>
  <Head title="商品 新規登録" />

  <div class="max-w-3xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">商品 新規登録</h1>
      <Link :href="route('items.index')" class="text-gray-600 hover:underline">一覧へ戻る</Link>
    </div>

    <form @submit.prevent="submit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium mb-1">SKU <span class="text-red-500">*</span></label>
        <input v-model="form.sku" type="text" class="w-full border rounded px-3 py-2" />
        <div v-if="form.errors.sku" class="text-red-600 text-sm mt-1">{{ form.errors.sku }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">名前 <span class="text-red-500">*</span></label>
        <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
        <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">カテゴリー</label>
        <select v-model="form.category_id" class="w-full border rounded px-3 py-2">
          <option :value="null">— 未選択 —</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
        <div v-if="form.errors.category_id" class="text-red-600 text-sm mt-1">{{ form.errors.category_id }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">標準原価 <span class="text-red-500">*</span></label>
        <input v-model="form.standard_cost" type="number" step="0.01" class="w-full border rounded px-3 py-2" />
        <div v-if="form.errors.standard_cost" class="text-red-600 text-sm mt-1">{{ form.errors.standard_cost }}</div>
      </div>

        <div>
        <label>単位</label>
        <input v-model="form.unit" type="text" />
        </div>

        <div>
        <label>
            <input v-model="form.is_active" type="checkbox" />
            有効
        </label>
        </div>

      <div class="flex gap-3">
        <button type="submit" :disabled="form.processing"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-60">
          登録する
        </button>
        <Link :href="route('items.index')" class="px-4 py-2 rounded border">キャンセル</Link>
      </div>
    </form>
  </div>
</template>
