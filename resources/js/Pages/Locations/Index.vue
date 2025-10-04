<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  locations: Object,
  filters: Object,
  can: Object,
})

// 検索フォーム
const form = useForm({
  keyword: props.filters?.keyword ?? '',
})

const search = () => {
  form.get(route('locations.index'), {
    preserveState: true,
    replace: true,
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="ロケーション一覧" />

    <div class="max-w-7xl mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">ロケーション一覧</h1>
        <Link
          v-if="can.create"
          :href="route('locations.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          新規登録
        </Link>
      </div>

      <!-- 検索フォーム -->
      <form @submit.prevent="search" class="mb-4 flex gap-2">
        <input
          v-model="form.keyword"
          type="text"
          placeholder="名前で検索"
          class="border rounded px-3 py-2"
        />
        <button
          type="submit"
          class="bg-gray-800 text-white px-3 py-2 rounded"
        >
          検索
        </button>
      </form>

      <!-- 一覧テーブル -->
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border w-16">ID</th>
            <th class="px-4 py-2 border">名前</th>
            <th class="px-4 py-2 border w-24">状態</th>
            <th class="px-4 py-2 border w-48">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="l in locations.data"
            :key="l.id"
            class="hover:bg-gray-50"
          >
            <td class="px-4 py-2 border font-mono">{{ l.id }}</td>
            <td class="px-4 py-2 border">{{ l.name }}</td>
            <td class="px-4 py-2 border">
              <span v-if="l.is_active" class="text-green-600">有効</span>
              <span v-else class="text-gray-400">無効</span>
            </td>
            <td class="px-4 py-2 border">
              <Link
                :href="route('locations.show', l.id)"
                class="text-gray-700 underline"
              >
                詳細
              </Link>
              <Link
                v-if="l.can?.update"
                :href="route('locations.edit', l.id)"
                class="ml-3 text-blue-600 hover:underline"
              >
                編集
              </Link>
              <Link
                v-if="l.can?.delete"
                as="button"
                method="delete"
                :href="route('locations.destroy', l.id)"
                class="ml-3 text-red-600 hover:underline"
                onclick="return confirm('削除しますか？')"
              >
                削除
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>
