<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Gift Types</h2>
      <router-link 
        to="/admin/gift-types/create" 
        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Gift Type
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
      <div class="p-4 border-b border-gray-200">
        <div class="flex gap-2">
          <input 
            v-model="search" 
            @keyup.enter="fetchGiftTypes" 
            placeholder="Search gift types..." 
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
          <button 
            @click="fetchGiftTypes" 
            class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
          >
            Search
          </button>
        </div>
      </div>

      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-600">Loading...</p>
      </div>

      <div v-else-if="giftTypes.data.length === 0" class="p-8 text-center text-gray-500">
        No gift types found
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in giftTypes.data" :key="item.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <img 
                  v-if="item.image_url" 
                  :src="item.image_url" 
                  :alt="item.name"
                  class="h-12 w-12 rounded-lg object-cover"
                />
                <div v-else class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-600">{{ item.slug }}</div>
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
                  :to="`/admin/gift-types/${item.id}/edit`" 
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

      <div v-if="giftTypes.data.length > 0" class="p-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-600">
          Showing {{ giftTypes.from }} to {{ giftTypes.to }} of {{ giftTypes.total }} results
        </div>
        <div class="flex gap-2">
          <button 
            :disabled="!giftTypes.prev_page_url" 
            @click="changePage(giftTypes.current_page - 1)" 
            class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button 
            :disabled="!giftTypes.next_page_url" 
            @click="changePage(giftTypes.current_page + 1)" 
            class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import giftTypeService from '../../../services/giftTypeService';

const giftTypes = reactive({ 
  data: [], 
  current_page: 1, 
  last_page: 1, 
  prev_page_url: null, 
  next_page_url: null,
  from: 0,
  to: 0,
  total: 0
});

const loading = ref(false);
const search = ref('');

const fetchGiftTypes = async (page = 1) => {
  loading.value = true;
  try {
    const res = await giftTypeService.list({ page, search: search.value });
    Object.assign(giftTypes, res.data.data);
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page < 1 || page > giftTypes.last_page) return;
  fetchGiftTypes(page);
};

const confirmDelete = (id) => {
  if (!confirm('Delete this gift type?')) return;
  deleteGiftType(id);
};

const deleteGiftType = async (id) => {
  try {
    await giftTypeService.destroy(id);
    fetchGiftTypes(giftTypes.current_page);
  } catch (e) {
    alert('Delete failed');
  }
};

onMounted(() => {
  fetchGiftTypes();
});
</script>
