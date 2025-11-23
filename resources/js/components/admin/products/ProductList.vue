<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Products</h2>
      <router-link to="/admin/products/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
        Add Product
      </router-link>
    </div>

    <div class="mb-6 flex gap-3">
      <input 
        v-model="search" 
        @keyup.enter="fetchProducts" 
        placeholder="Search title or slug..." 
        class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
      />
      <button 
        @click="fetchProducts" 
        class="bg-gray-100 hover:bg-gray-200 px-6 py-2 rounded-lg font-medium transition-colors"
      >
        Search
      </button>
    </div>

    <div v-if="loading" class="p-8 bg-white rounded-lg shadow text-center text-gray-500">Loading...</div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in products.data" :key="item.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <img 
                v-if="item.image_medium_url" 
                :src="item.image_medium_url" 
                :alt="item.title"
                class="h-12 w-12 rounded-lg object-cover"
              />
              <div v-else class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm font-medium text-gray-900">{{ item.title }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-600">{{ item.slug }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-semibold text-gray-900">${{ item.price }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="item.status === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              >
                {{ item.status === 1 ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <router-link
                :to="`/admin/products/${item.id}/edit`"
                class="inline-flex items-center text-blue-600 hover:text-blue-900"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </router-link>
              <button
                @click="confirmDelete(item.id)"
                class="inline-flex items-center text-red-600 hover:text-red-900"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-6 flex items-center justify-between">
      <div class="flex gap-2">
        <button 
          :disabled="!products.prev_page_url" 
          @click="changePage(products.current_page - 1)" 
          :class="products.prev_page_url ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-gray-50 text-gray-400 cursor-not-allowed'"
          class="px-4 py-2 rounded-lg font-medium transition-colors"
        >
          Previous
        </button>
        <button 
          :disabled="!products.next_page_url" 
          @click="changePage(products.current_page + 1)" 
          :class="products.next_page_url ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-gray-50 text-gray-400 cursor-not-allowed'"
          class="px-4 py-2 rounded-lg font-medium transition-colors"
        >
          Next
        </button>
      </div>
      <div class="text-sm font-medium text-gray-600">
        Page {{ products.current_page }} of {{ products.last_page }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import productService from '../../../services/productService';

const products = reactive({ 
  data: [], 
  current_page: 1, 
  last_page: 1, 
  prev_page_url: null, 
  next_page_url: null 
});

const loading = ref(false);
const search = ref('');

const fetchProducts = async (page = 1) => {
  loading.value = true;
  try {
    const res = await productService.list({ page, search: search.value });
    Object.assign(products, res.data.data);
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page < 1 || page > products.last_page) return;
  fetchProducts(page);
};

const confirmDelete = (id) => {
  if (!confirm('Delete this product?')) return;
  deleteProduct(id);
};

const deleteProduct = async (id) => {
  try {
    await productService.destroy(id);
    fetchProducts(products.current_page);
  } catch (e) {
    alert('Delete failed');
  }
};

onMounted(() => {
  fetchProducts();
});
</script>
