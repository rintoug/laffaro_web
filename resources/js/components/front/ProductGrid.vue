<template>
  <div>
    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
      <a 
        v-for="product in products" 
        :key="product.id"
        :href="`/product/${product.slug}`" 
        class="group"
      >
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden border border-gray-100">
          <!-- Product Image -->
          <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
            <img 
              v-if="product.image_medium_url"
              :src="product.image_medium_url" 
              :alt="product.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
              </svg>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors duration-300">
              {{ product.title }}
            </h3>
            
            <p v-if="product.short_description" class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
              {{ product.short_description }}
            </p>
          </div>
        </div>
      </a>
    </div>

    <!-- Loading Indicator -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Load More Trigger (invisible) -->
    <div ref="loadMoreTrigger" class="h-10"></div>

    <!-- End Message -->
    <div v-if="!loading && !hasMore && products.length > 0" class="text-center py-12">
      <p class="text-gray-500 text-lg">You've reached the end of the list</p>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && products.length === 0" class="text-center py-24">
      <div class="inline-block p-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl mb-6">
        <svg class="mx-auto h-24 w-24 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
      </div>
      <h3 class="text-3xl font-bold text-gray-900 mb-3">No products found</h3>
      <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">We couldn't find any products matching your criteria. Try browsing all products or adjusting your filters.</p>
      <a href="/" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-200 space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>View All Products</span>
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const props = defineProps({
  initialProducts: {
    type: Array,
    required: true
  },
  initialCount: {
    type: Number,
    required: true
  },
  categorySlug: {
    type: String,
    default: null
  },
  giftTypeSlug: {
    type: String,
    default: null
  },
  searchTerm: {
    type: String,
    default: null
  }
});

const products = ref([...props.initialProducts]);
const loading = ref(false);
const currentPage = ref(5); // Start from page 5 (40 items already shown, 8 per page in API)
const hasMore = ref(props.initialCount >= 40); // Only load more if we have 40 items
const loadMoreTrigger = ref(null);
let observer = null;

const fetchProducts = async () => {
  if (loading.value || !hasMore.value) return;

  loading.value = true;

  try {
    const params = {
      page: currentPage.value,
      per_page: 8
    };

    if (props.categorySlug) {
      params.category = props.categorySlug;
    }
    if (props.giftTypeSlug) {
      params.gift_type = props.giftTypeSlug;
    }
    if (props.searchTerm) {
      params.search = props.searchTerm;
    }

    const response = await axios.get('/api/products', { params });
    
    const newProducts = response.data.data;
    
    if (newProducts.length === 0) {
      hasMore.value = false;
    } else {
      products.value.push(...newProducts);
      currentPage.value++;
      
      // Check if we're on the last page
      if (response.data.current_page >= response.data.last_page) {
        hasMore.value = false;
      }
    }
  } catch (error) {
    console.error('Failed to fetch products:', error);
  } finally {
    loading.value = false;
  }
};

const setupIntersectionObserver = () => {
  observer = new IntersectionObserver(
    (entries) => {
      if (entries[0].isIntersecting && hasMore.value && !loading.value) {
        fetchProducts();
      }
    },
    {
      rootMargin: '200px' // Start loading before user reaches the bottom
    }
  );

  if (loadMoreTrigger.value) {
    observer.observe(loadMoreTrigger.value);
  }
};

onMounted(() => {
  // Only setup observer, don't fetch on mount since we have initial products
  setupIntersectionObserver();
});

onBeforeUnmount(() => {
  if (observer) {
    observer.disconnect();
  }
});
</script>
