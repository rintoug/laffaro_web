<template>
    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <article v-for="article in articles" :key="article.id" class="group">
                <a :href="`/articles/${article.slug}`" class="block bg-white rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden h-full">
                    <!-- Article Image -->
                    <div class="aspect-square overflow-hidden bg-gradient-to-br from-purple-100 to-pink-100">
                        <img 
                            v-if="article.image_url"
                            :src="article.image_url" 
                            :alt="article.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        >
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>

                    <!-- Article Content -->
                    <div class="p-5">
                        <h2 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                            {{ article.title }}
                        </h2>
                        
                        <!-- View Guide Button -->
                        <span class="inline-flex items-center text-purple-600 font-medium text-sm group-hover:translate-x-1 transition-transform mt-2">
                            View Guide
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            </article>
        </div>
        <div ref="loadMoreTrigger" class="load-more-trigger"></div>
        <div v-if="loading" class="loading-indicator">Loading more articles...</div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    initialCount: {
        type: Number,
        required: true
    },
    searchTerm: {
        type: String,
        default: null
    }
});

const articles = ref([]);
const loading = ref(false);
const currentPage = ref(5); // Start from page 5 (40 items already shown, 8 per page)
const hasMore = ref(props.initialCount >= 40);
const loadMoreTrigger = ref(null);
let observer = null;

const loadMore = async () => {
    if (loading.value || !hasMore.value) return;

    loading.value = true;

    try {
        const params = new URLSearchParams({
            page: currentPage.value,
            per_page: 8
        });

        if (props.searchTerm) {
            params.append('search', props.searchTerm);
        }

        const response = await fetch(`/api/articles?${params.toString()}`);
        const data = await response.json();

        if (data.data && data.data.length > 0) {
            articles.value.push(...data.data);
            currentPage.value++;
            hasMore.value = data.current_page < data.last_page;
        } else {
            hasMore.value = false;
        }
    } catch (error) {
        console.error('Error loading more articles:', error);
        hasMore.value = false;
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

onMounted(() => {
    if (hasMore.value) {
        observer = new IntersectionObserver(
            (entries) => {
                if (entries[0].isIntersecting && !loading.value) {
                    loadMore();
                }
            },
            {
                rootMargin: '200px'
            }
        );

        if (loadMoreTrigger.value) {
            observer.observe(loadMoreTrigger.value);
        }
    }
});

onBeforeUnmount(() => {
    if (observer) {
        observer.disconnect();
    }
});
</script>

<style scoped>
.load-more-trigger {
    height: 1px;
}

.loading-indicator {
    text-align: center;
    padding: 2rem;
    color: #666;
}
</style>
