<template>
  <div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
      <h1 class="text-3xl font-bold mb-2">Welcome back, {{ userName }}! 👋</h1>
      <p class="text-indigo-100">Here's what's happening with your store today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Products Stat -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
            Active
          </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ stats.products }}</h3>
        <p class="text-sm text-gray-600">Total Products</p>
        <router-link to="/admin/products" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
          View all →
        </router-link>
      </div>

      <!-- Categories Stat -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
          </div>
          <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">
            {{ stats.categories }}
          </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ stats.categories }}</h3>
        <p class="text-sm text-gray-600">Categories</p>
        <router-link to="/admin/categories" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
          Manage →
        </router-link>
      </div>

      <!-- Gift Types Stat -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
            </svg>
          </div>
          <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-2 py-1 rounded-full">
            Types
          </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ stats.giftTypes }}</h3>
        <p class="text-sm text-gray-600">Gift Types</p>
        <router-link to="/admin/gift-types" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
          View all →
        </router-link>
      </div>

      <!-- Blog Posts Stat -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
          <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
            Posts
          </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ stats.blog }}</h3>
        <p class="text-sm text-gray-600">Blog Posts</p>
        <router-link to="/admin/blog" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
          Manage →
        </router-link>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <router-link 
          to="/admin/products/create"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all group"
        >
          <svg class="w-8 h-8 text-gray-400 group-hover:text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">Add Product</span>
        </router-link>

        <router-link 
          to="/admin/categories"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group"
        >
          <svg class="w-8 h-8 text-gray-400 group-hover:text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
          <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Manage Categories</span>
        </router-link>

        <router-link 
          to="/admin/blog"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-amber-500 hover:bg-amber-50 transition-all group"
        >
          <svg class="w-8 h-8 text-gray-400 group-hover:text-amber-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          <span class="text-sm font-medium text-gray-700 group-hover:text-amber-700">Write Blog Post</span>
        </router-link>

        <router-link 
          to="/admin/articles"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group"
        >
          <svg class="w-8 h-8 text-gray-400 group-hover:text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">New Article</span>
        </router-link>
      </div>
    </div>

    <!-- Recent Activity placeholder -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">System Status</h2>
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">Database</span>
            <span class="flex items-center text-sm font-medium text-green-600">
              <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
              Connected
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">API</span>
            <span class="flex items-center text-sm font-medium text-green-600">
              <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
              Operational
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">Storage</span>
            <span class="flex items-center text-sm font-medium text-green-600">
              <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
              Available
            </span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h2>
        <div class="space-y-3">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-indigo-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <p class="text-sm font-medium text-gray-900">All systems operational</p>
              <p class="text-xs text-gray-500 mt-0.5">Last checked: just now</p>
            </div>
          </div>
          <div class="flex items-start">
            <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <p class="text-sm font-medium text-gray-900">Admin access active</p>
              <p class="text-xs text-gray-500 mt-0.5">Logged in as {{ userName }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from '../../axios';

const stats = reactive({
  products: 0,
  categories: 0,
  giftTypes: 0,
  blog: 0
});

const userName = computed(() => {
  const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
  return user.name || 'Admin';
});

const loadStats = async () => {
  try {
    // Load product count
    const productsRes = await axios.get('/admin/products', { params: { per_page: 1 } });
    stats.products = productsRes.data.data.total || 0;

    // Load category count
    const categoriesRes = await axios.get('/admin/categories', { params: { per_page: 1 } });
    stats.categories = categoriesRes.data.data.total || 0;

    // Load gift types count
    const giftTypesRes = await axios.get('/admin/gift-types', { params: { per_page: 1 } });
    stats.giftTypes = giftTypesRes.data.data.total || 0;

    // Load blog count
    const blogRes = await axios.get('/admin/blog', { params: { per_page: 1 } });
    stats.blog = blogRes.data.data.total || 0;
  } catch (e) {
    console.error('Failed to load stats:', e);
  }
};

onMounted(() => {
  loadStats();
});
</script>
