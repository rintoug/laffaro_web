import { createApp } from 'vue';
import ProductInfiniteScroll from './components/front/ProductInfiniteScroll.vue';
import BlogInfiniteScroll from './components/front/BlogInfiniteScroll.vue';
import ArticleInfiniteScroll from './components/front/ArticleInfiniteScroll.vue';
import axios from 'axios';

// Configure axios
axios.defaults.baseURL = window.location.origin;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Mount Vue components only where they exist
document.addEventListener('DOMContentLoaded', () => {
    const infiniteScrollContainer = document.getElementById('infinite-scroll-container');
    
    if (infiniteScrollContainer) {
        const app = createApp({});
        app.component('product-infinite-scroll', ProductInfiniteScroll);
        app.component('blog-infinite-scroll', BlogInfiniteScroll);
        app.component('article-infinite-scroll', ArticleInfiniteScroll);
        app.mount('#infinite-scroll-container');
    }
});
