<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  is_active: true,
})



const submit = () => {
  form.post(route('categories.store'))
}
</script>

<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">カテゴリー新規作成</h1>

    <form @submit.prevent="submit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium mb-1">名前</label>
        <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
        <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
      </div>

      <div class="flex items-center gap-2">
        <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4" />
        <label for="is_active">有効</label>
      </div>

      <div class="flex gap-3">
        <button type="submit" :disabled="form.processing"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          登録する
        </button>
        <Link :href="route('categories.index')" class="px-4 py-2 border rounded">戻る</Link>
      </div>
    </form>
  </div>
</template>
