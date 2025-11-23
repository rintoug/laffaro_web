<template>
  <div class="min-h-screen flex bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
      <!-- Logo/Brand -->
      <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <div class="flex items-center">
          <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-sm">A</span>
          </div>
          <span class="ml-3 font-bold text-gray-900">Admin Panel</span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 px-4 py-6 overflow-y-auto">
        <div class="space-y-1">
          <!-- Dashboard -->
          <router-link 
            to="/admin" 
            exact
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            active-class="bg-indigo-50 text-indigo-700"
            inactive-class="text-gray-700 hover:bg-gray-50"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
          </router-link>

          <!-- Products -->
          <router-link 
            to="/admin/products"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            active-class="bg-indigo-50 text-indigo-700"
            inactive-class="text-gray-700 hover:bg-gray-50"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Products
          </router-link>

          <!-- Categories -->
          <router-link 
            to="/admin/categories"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            active-class="bg-indigo-50 text-indigo-700"
            inactive-class="text-gray-700 hover:bg-gray-50"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Categories
          </router-link>

          <!-- Gift Types -->
          <router-link 
            to="/admin/gift-types"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            active-class="bg-indigo-50 text-indigo-700"
            inactive-class="text-gray-700 hover:bg-gray-50"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
            </svg>
            Gift Types
          </router-link>

          <!-- Articles -->
          <router-link 
            to="/admin/articles"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            active-class="bg-indigo-50 text-indigo-700"
            inactive-class="text-gray-700 hover:bg-gray-50"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Articles
          </router-link>

          <!-- Blogs -->
          <router-link 
            to="/admin/blogs"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            active-class="bg-indigo-50 text-indigo-700"
            inactive-class="text-gray-700 hover:bg-gray-50"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            Blogs
          </router-link>
        </div>
      </nav>

      <!-- User Profile / Logout -->
      <div class="border-t border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center min-w-0">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
              <span class="text-white font-semibold text-sm">{{ userInitial }}</span>
            </div>
            <div class="ml-3 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">{{ userName }}</p>
              <p class="text-xs text-gray-500">Administrator</p>
            </div>
          </div>
        </div>
        <button 
          @click="logout"
          class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Sign Out
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Bar -->
      <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
        <div>
          <h1 class="text-xl font-semibold text-gray-900">{{ pageTitle }}</h1>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-gray-600">{{ currentDate }}</span>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from '../../axios';

const router = useRouter();
const route = useRoute();

const currentDate = ref(new Date().toLocaleDateString('en-US', { 
  weekday: 'long', 
  year: 'numeric', 
  month: 'long', 
  day: 'numeric' 
}));

const userName = computed(() => {
  const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
  return user.name || 'Admin';
});

const userInitial = computed(() => {
  return userName.value.charAt(0).toUpperCase();
});

const pageTitle = computed(() => {
  const routeName = route.name;
  if (routeName === 'Dashboard') return 'Dashboard';
  if (routeName === 'Products' || routeName === 'ProductCreate' || routeName === 'ProductEdit') return 'Products';
  if (routeName === 'Categories' || routeName === 'CategoryCreate' || routeName === 'CategoryEdit') return 'Categories';
  if (routeName === 'GiftTypes' || routeName === 'GiftTypeCreate' || routeName === 'GiftTypeEdit') return 'Gift Types';
  if (routeName === 'Articles' || routeName === 'ArticleCreate' || routeName === 'ArticleEdit') return 'Articles';
  if (routeName === 'Blogs' || routeName === 'BlogCreate' || routeName === 'BlogEdit') return 'Blogs';
  return 'Admin';
});

const logout = async () => {
  try {
    await axios.post('/admin/logout');
  } catch (e) {
    console.error('Logout error:', e);
  } finally {
    localStorage.removeItem('admin_token');
    localStorage.removeItem('admin_user');
    delete axios.defaults.headers.common['Authorization'];
    router.push({ name: 'AdminLogin' });
  }
};
</script>
