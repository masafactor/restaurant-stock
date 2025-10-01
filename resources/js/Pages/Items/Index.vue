<script setup>
import { Head, Link, router,useForm  } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  items: Object,
  filters: Object,
  can: Object,
})

const form = useForm({
  keyword: props.filters.keyword || ''
})

const submit = () => {
  form.get(route('items.index'), { preserveState: true, replace: true })
}

function destroyItem(item) {
  if (!confirm('本当に削除しますか？')) return
  router.delete(route('items.destroy', item.id), {
    preserveScroll: true,
    onSuccess: () => {
      // ここでトーストを出したり、一覧を再読込せずに行を手で抜くことも可
      // 例: props.items.data = props.items.data.filter(x => x.id !== item.id)
    },
  })
}
</script>

<template>
<AuthenticatedLayout>
  <Head title="商品一覧" />

  <div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">商品一覧</h1>

    <Link
      v-if="can.create"
      :href="route('items.create')"
      class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 "
    >
      新規登録
    </Link>

    <!-- 🔍 検索フォーム -->
    <form @submit.prevent="submit" class="mb-4 flex gap-2 mt-6">
      <input
        v-model="form.keyword"
        type="text"
        placeholder="商品名またはSKUで検索"
        class="border rounded px-3 py-2 w-64"
      />
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        検索
      </button>
    </form>

    <table class="min-w-full border border-gray-200 mt-10">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 border">ID</th>
          <th class="px-4 py-2 border">SKU</th>
          <th class="px-4 py-2 border">名前</th>
          <th class="px-4 py-2 border">カテゴリー</th>
          <th class="px-4 py-2 border">標準原価</th>
          <th class="px-4 py-2 border">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items.data" :key="item.id" class="hover:bg-gray-50">
          <td class="px-4 py-2 border">{{ item.id }}</td>
          <td class="px-4 py-2 border">{{ item.sku }}</td>
          <td class="px-4 py-2 border">{{ item.name }}</td>
          <td class="px-4 py-2 border">{{ item.category?.name ?? '-' }}</td>
          <td class="px-4 py-2 border">{{ item.standard_cost }}</td>
          <td class="px-4 py-2 border">
          <Link :href="route('items.show', item.id)" class="text-gray-700 underline">詳細</Link>
              <Link
    v-if="item.can.update"
    :href="route('items.edit', item.id)"
    class="text-blue-500"
  >
    編集
  </Link>
  <Link
    v-if="item.can.delete"
    as="button"
    method="delete"
    :href="route('items.destroy', item.id)"
    class="ml-2 text-red-500"
    onclick="return confirm('本当に削除しますか？')"
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
