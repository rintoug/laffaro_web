<template>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <article v-for="blog in blogs" :key="blog.id" class="group">
                <a :href="`/blogs/${blog.slug}`" class="block bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <!-- Blog Image -->
                    <div class="aspect-video overflow-hidden bg-gray-100">
                        <img 
                            v-if="blog.image_url"
                            :src="blog.image_url" 
                            :alt="blog.title"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Blog Content -->
                    <div class="p-6">
                        <!-- Meta Info -->
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <span v-if="blog.author" class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ blog.author }}
                            </span>
                            <span v-if="blog.author" class="mx-2">•</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ formatDate(blog.created_at) }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                            {{ blog.title }}
                        </h2>
                        
                        <!-- Excerpt -->
                        <p v-if="blog.short_description" class="text-gray-600 mb-4 line-clamp-3">
                            {{ blog.short_description }}
                        </p>

                        <!-- Read More -->
                        <span class="inline-flex items-center text-indigo-600 font-medium group-hover:translate-x-1 transition-transform">
                            Read More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            </article>
        </div>
        <div ref="loadMoreTrigger" class="load-more-trigger"></div>
        <div v-if="loading" class="loading-indicator">Loading more blogs...</div>
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

const blogs = ref([]);
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

        const response = await fetch(`/api/blogs?${params.toString()}`);
        const data = await response.json();

        if (data.data && data.data.length > 0) {
            blogs.value.push(...data.data);
            currentPage.value++;
            hasMore.value = data.current_page < data.last_page;
        } else {
            hasMore.value = false;
        }
    } catch (error) {
        console.error('Error loading more blogs:', error);
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
