<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Gift Articles</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your gift articles and their products</p>
      </div>
      <router-link
        to="/admin/articles/create"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Article
      </router-link>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
      <div class="flex gap-4">
        <div class="flex-1">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Search articles..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
        <select
          v-model="perPage"
          @change="loadArticles"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option value="10">10 per page</option>
          <option value="25">25 per page</option>
          <option value="50">50 per page</option>
          <option value="100">100 per page</option>
        </select>
      </div>
    </div>

    <!-- Articles Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
        <p class="mt-2 text-gray-600">Loading articles...</p>
      </div>

      <div v-else-if="error" class="p-8 text-center text-red-600">
        {{ error }}
      </div>

      <div v-else-if="articles.length === 0" class="p-8 text-center text-gray-500">
        No articles found. Create your first article!
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="article in articles" :key="article.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <img 
                  v-if="article.image" 
                  :src="article.image" 
                  :alt="article.title"
                  class="h-12 w-12 rounded-lg object-cover"
                />
                <div v-else class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ article.title }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-600">{{ article.slug }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ article.products_count || 0 }} products
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="article.status === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ article.status === 1 ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <router-link
                  :to="`/admin/articles/${article.id}/edit`"
                  class="inline-flex items-center text-blue-600 hover:text-blue-900"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </router-link>
                <button
                  @click="confirmDelete(article)"
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

      <!-- Pagination -->
      <div v-if="pagination.total > 0" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} articles
          </div>
          <div class="flex gap-2">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="changePage(page)"
              :disabled="page === pagination.current_page"
              :class="page === pagination.current_page 
                ? 'bg-blue-600 text-white' 
                : 'bg-white text-gray-700 hover:bg-gray-50'"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              {{ page }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="showDeleteModal = false"
    >
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Delete Article</h3>
        <p class="text-gray-600 mb-6">
          Are you sure you want to delete "{{ articleToDelete?.title }}"? This action cannot be undone.
        </p>
        <div class="flex justify-end gap-3">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deleteArticle"
            :disabled="deleting"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors"
          >
            {{ deleting ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import articleService from '../../../services/articleService';

const articles = ref([]);
const loading = ref(false);
const error = ref(null);
const searchQuery = ref('');
const perPage = ref(15);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0,
  from: 0,
  to: 0
});

const showDeleteModal = ref(false);
const articleToDelete = ref(null);
const deleting = ref(false);

let searchTimeout = null;

const visiblePages = computed(() => {
  const pages = [];
  const current = pagination.value.current_page;
  const last = pagination.value.last_page;
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i);
  }
  
  return pages;
});

const loadArticles = async (page = 1) => {
  loading.value = true;
  error.value = null;

  try {
    const params = {
      page,
      per_page: perPage.value,
      search: searchQuery.value
    };

    const response = await articleService.getAll(params);
    
    articles.value = response.data.data.data;
    pagination.value = {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      per_page: response.data.data.per_page,
      total: response.data.data.total,
      from: response.data.data.from,
      to: response.data.data.to
    };
  } catch (e) {
    console.error('Failed to load articles:', e);
    console.error('Response:', e.response?.data);
    console.error('Status:', e.response?.status);
    error.value = e.response?.data?.message || 'Failed to load articles. Please try again.';
  } finally {
    loading.value = false;
  }
};

const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    loadArticles(1);
  }, 500);
};

const changePage = (page) => {
  loadArticles(page);
};

const confirmDelete = (article) => {
  articleToDelete.value = article;
  showDeleteModal.value = true;
};

const deleteArticle = async () => {
  if (!articleToDelete.value) return;

  deleting.value = true;
  try {
    await articleService.delete(articleToDelete.value.id);
    showDeleteModal.value = false;
    articleToDelete.value = null;
    loadArticles(pagination.value.current_page);
  } catch (e) {
    console.error('Failed to delete article:', e);
    error.value = 'Failed to delete article. Please try again.';
  } finally {
    deleting.value = false;
  }
};

onMounted(() => {
  loadArticles();
});
</script>
