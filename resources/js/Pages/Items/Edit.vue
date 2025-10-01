<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  item: Object,
  categories: Array,
})

const form = useForm({
  sku:           props.item.sku ?? '',
  name:          props.item.name ?? '',
  unit:          props.item.unit ?? '',
  standard_cost: props.item.standard_cost ?? 0,
  is_active:     !!props.item.is_active,
  category_id:   props.item.category_id ?? null,
})

function submit() {
  form.put(route('items.update', props.item.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Head title="商品編集" />

  <div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">商品編集</h1>
      <Link :href="route('items.index')" class="text-blue-600">一覧に戻る</Link>
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">SKU</label>
        <input v-model="form.sku" type="text" class="w-full border rounded p-2" />
        <div v-if="form.errors.sku" class="text-red-600 text-sm mt-1">{{ form.errors.sku }}</div>
      </div>

      <div>
        <label class="block text-sm text-gray-600 mb-1">名前</label>
        <input v-model="form.name" type="text" class="w-full border rounded p-2" />
        <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-600 mb-1">単位</label>
          <input v-model="form.unit" type="text" class="w-full border rounded p-2" />
          <div v-if="form.errors.unit" class="text-red-600 text-sm mt-1">{{ form.errors.unit }}</div>
        </div>

        <div>
          <label class="block text-sm text-gray-600 mb-1">標準原価</label>
          <input v-model.number="form.standard_cost" type="number" step="0.01" min="0" class="w-full border rounded p-2" />
          <div v-if="form.errors.standard_cost" class="text-red-600 text-sm mt-1">{{ form.errors.standard_cost }}</div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-600 mb-1">カテゴリー</label>
          <select v-model="form.category_id" class="w-full border rounded p-2">
            <option :value="null">未設定</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
          <div v-if="form.errors.category_id" class="text-red-600 text-sm mt-1">{{ form.errors.category_id }}</div>
        </div>

        <div class="flex items-end">
          <label class="inline-flex items-center">
            <input v-model="form.is_active" type="checkbox" class="mr-2" />
            有効
          </label>
        </div>
      </div>

      <div class="pt-4">
        <button
          type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
          :disabled="form.processing"
        >
          保存
        </button>
      </div>
    </form>
  </div>
</template>
