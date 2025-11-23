import { createRouter, createWebHistory } from 'vue-router';
import axios from '../axios';

import AdminLayout from '../components/admin/Layout.vue';
import Login from '../components/admin/Login.vue';
import Dashboard from '../components/admin/Dashboard.vue';

import ProductsList from '../components/admin/products/ProductList.vue';
import ProductForm from '../components/admin/products/ProductForm.vue';
import CategoriesList from '../components/admin/categories/CategoryList.vue';
import CategoryForm from '../components/admin/categories/CategoryForm.vue';
import GiftTypesList from '../components/admin/gift-types/GiftTypeList.vue';
import GiftTypeForm from '../components/admin/gift-types/GiftTypeForm.vue';
import ArticlesList from '../components/admin/articles/ArticleList.vue';
import ArticleForm from '../components/admin/articles/ArticleForm.vue';
import BlogsList from '../components/admin/blogs/BlogList.vue';
import BlogForm from '../components/admin/blogs/BlogForm.vue';

const routes = [
  { 
    path: '/admin/login', 
    name: 'AdminLogin', 
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'Dashboard', component: Dashboard },
      
      // Products
      { path: 'products', name: 'Products', component: ProductsList },
      { path: 'products/create', name: 'ProductCreate', component: ProductForm },
      { path: 'products/:id/edit', name: 'ProductEdit', component: ProductForm, props: true },
      
      // Categories
      { path: 'categories', name: 'Categories', component: CategoriesList },
      { path: 'categories/create', name: 'CategoryCreate', component: CategoryForm },
      { path: 'categories/:id/edit', name: 'CategoryEdit', component: CategoryForm, props: true },
      
      // Gift Types
      { path: 'gift-types', name: 'GiftTypes', component: GiftTypesList },
      { path: 'gift-types/create', name: 'GiftTypeCreate', component: GiftTypeForm },
      { path: 'gift-types/:id/edit', name: 'GiftTypeEdit', component: GiftTypeForm, props: true },
      
      // Articles
      { path: 'articles', name: 'Articles', component: ArticlesList },
      { path: 'articles/create', name: 'ArticleCreate', component: ArticleForm },
      { path: 'articles/:id/edit', name: 'ArticleEdit', component: ArticleForm, props: true },
      
      // Blogs
      { path: 'blogs', name: 'Blogs', component: BlogsList },
      { path: 'blogs/create', name: 'BlogCreate', component: BlogForm },
      { path: 'blogs/:id/edit', name: 'BlogEdit', component: BlogForm, props: true },
    ],
  },
  // Redirect root to admin
  { path: '/', redirect: '/admin' },
  // Catch all undefined routes and redirect to login
  { 
    path: '/:pathMatch(.*)*', 
    redirect: (to) => {
      const token = localStorage.getItem('admin_token');
      const user = localStorage.getItem('admin_user');
      
      if (token && user) {
        return '/admin';
      }
      return '/admin/login';
    }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard to protect admin routes
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('admin_token');
  const user = localStorage.getItem('admin_user');
  
  // Check if route requires authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!token || !user) {
      // Not authenticated, redirect to login
      next({ name: 'AdminLogin' });
      return;
    }
    
    // Verify user is admin type
    const userData = JSON.parse(user);
    if (userData.type !== 'admin') {
      // Not an admin, clear storage and redirect to login
      localStorage.removeItem('admin_token');
      localStorage.removeItem('admin_user');
      delete axios.defaults.headers.common['Authorization'];
      next({ name: 'AdminLogin' });
      return;
    }
    
    // Authenticated and admin, allow access
    next();
  } else if (to.matched.some(record => record.meta.guest)) {
    // Guest routes (login page)
    if (token && user) {
      const userData = JSON.parse(user);
      if (userData.type === 'admin') {
        // Already logged in as admin, redirect to dashboard
        next({ name: 'Dashboard' });
        return;
      }
    }
    next();
  } else {
    next();
  }
});

export default router;