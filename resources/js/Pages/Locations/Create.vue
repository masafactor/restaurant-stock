<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const form = useForm({
  name: '',
  is_active: true,
})

const submit = () => {
  form.post(route('locations.store'))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="ロケーション新規登録" />

    <div class="max-w-3xl mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">ロケーション新規登録</h1>
        <Link :href="route('locations.index')" class="text-gray-600 hover:underline">
          一覧へ戻る
        </Link>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- 名前 -->
        <div>
          <label class="block text-sm font-medium mb-1">名前 <span class="text-red-500">*</span></label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
          <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
        </div>

        <!-- 有効フラグ -->
        <div class="flex items-center gap-2">
          <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4" />
          <label for="is_active" class="text-sm">有効</label>
        </div>

        <!-- ボタン -->
        <div class="flex gap-3">
          <button type="submit" :disabled="form.processing"
                  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-60">
            登録する
          </button>
          <Link :href="route('locations.index')" class="px-4 py-2 rounded border">キャンセル</Link>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
