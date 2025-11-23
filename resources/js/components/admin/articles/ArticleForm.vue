<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">
          {{ isEdit ? 'Edit Article' : 'Create Article' }}
        </h1>
        <p class="mt-1 text-sm text-gray-600">
          {{ isEdit ? 'Update article details' : 'Add a new gift article with products' }}
        </p>
      </div>
      <router-link
        to="/admin/articles"
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Articles
      </router-link>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
      {{ error }}
    </div>

    <!-- Main Form -->
    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Article Details Card -->
      <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        <h2 class="text-lg font-semibold text-gray-900">Article Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Title -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Title <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.title"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Enter article title"
            />
          </div>

          <!-- Slug -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Slug <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.slug"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="article-slug"
            />
            <p class="mt-1 text-sm text-gray-500">URL-friendly version of the title</p>
          </div>

          <!-- Image Upload -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Featured Image
            </label>
            <div class="flex items-start gap-4">
              <div class="flex-1">
                <input
                  type="file"
                  @change="handleImageUpload"
                  accept="image/*"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="selectedFileName" class="mt-2 text-sm text-gray-600">
                  Selected: {{ selectedFileName }}
                </p>
              </div>
              <div v-if="imagePreview" class="flex-shrink-0">
                <img :src="imagePreview" alt="Preview" class="h-20 w-20 rounded-lg object-cover border border-gray-200" />
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Description
            </label>
            <div ref="editorContainer" class="prose max-w-none border border-gray-300 rounded-lg p-4 min-h-[300px]"></div>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Status <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.status"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Products Card -->
      <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        <h2 class="text-lg font-semibold text-gray-900">Article Products</h2>

        <!-- Product List -->
        <div class="space-y-4">
          <div
            v-for="(product, index) in form.products"
            :key="index"
            class="border border-gray-200 rounded-lg p-4 space-y-4 relative"
          >
            <!-- Remove Button -->
            <button
              v-if="form.products.length > 1"
              type="button"
              @click="removeProduct(index)"
              class="absolute top-4 right-4 text-red-600 hover:text-red-800"
              title="Remove product"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <h3 class="text-md font-medium text-gray-700">Product #{{ index + 1 }}</h3>

            <div class="space-y-4">
              <!-- Product Title -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Title <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="product.title"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter product title"
                />
              </div>

              <!-- Product Image -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Product Image <span class="text-red-500">*</span>
                </label>
                
                <!-- Image Preview -->
                <div v-if="product.imagePreview" class="mb-3">
                  <img 
                    :src="product.imagePreview" 
                    alt="Product preview" 
                    class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                  />
                  <button 
                    type="button"
                    @click="removeProductImage(index)"
                    class="mt-2 text-sm text-red-600 hover:text-red-800"
                  >
                    Remove Image
                  </button>
                </div>

                <!-- File Upload Button -->
                <label class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors w-fit">
                  <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-sm font-medium text-gray-700">Choose Image</span>
                  <input
                    type="file"
                    @change="handleProductImageUpload($event, index)"
                    accept="image/*"
                    class="hidden"
                  />
                </label>
              </div>

              <!-- Product Description -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Description <span class="text-red-500">*</span>
                </label>
                <textarea
                  v-model="product.description"
                  rows="3"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter product description"
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Price -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Price <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model.number="product.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0.00"
                  />
                </div>

                <!-- Affiliate Link -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Affiliate Link <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="product.aff_link"
                    type="url"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="https://"
                  />
                </div>

                <!-- Status -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="product.status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  >
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <p v-if="form.products.length === 0" class="text-center text-gray-500 py-8">
          No products added yet.
        </p>

        <!-- Add Product Button -->
        <div class="pt-4 border-t border-gray-200">
          <button
            type="button"
            @click="addProduct"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
          </button>
        </div>
      </div>

      <!-- SEO Card -->
      <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        <h2 class="text-lg font-semibold text-gray-900">SEO Settings</h2>

        <div class="space-y-4">
          <!-- Meta Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Meta Title
            </label>
            <input
              v-model="form.meta_title"
              type="text"
              maxlength="255"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="SEO title for search engines"
            />
          </div>

          <!-- Meta Keywords -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Meta Keywords
            </label>
            <input
              v-model="form.meta_keywords"
              type="text"
              maxlength="255"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="keyword1, keyword2, keyword3"
            />
          </div>

          <!-- Meta Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Meta Description
            </label>
            <textarea
              v-model="form.meta_description"
              rows="3"
              maxlength="500"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Brief description for search engines (max 500 characters)"
            ></textarea>
            <p class="mt-1 text-sm text-gray-500">
              {{ form.meta_description?.length || 0 }} / 500 characters
            </p>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end gap-4">
        <router-link
          to="/admin/articles"
          class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          Cancel
        </router-link>
        <button
          type="submit"
          :disabled="loading"
          class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
        >
          {{ loading ? 'Saving...' : (isEdit ? 'Update Article' : 'Create Article') }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import articleService from '../../../services/articleService';
import { useImageUpload } from '../../../composables/useImageUpload';
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Paragraph from '@editorjs/paragraph';
import Quote from '@editorjs/quote';
import Code from '@editorjs/code';
import Embed from '@editorjs/embed';
import Table from '@editorjs/table';
import LinkTool from '@editorjs/link';
import ImageTool from '@editorjs/image';

const router = useRouter();
const route = useRoute();

// Use image upload composable with 'articles' folder
const { uploadImage, getEditorImageConfig } = useImageUpload('articles');

const form = reactive({
  title: '',
  slug: '',
  image: '',
  description: '',
  status: '1',
  meta_title: '',
  meta_keywords: '',
  meta_description: '',
  products: [
    {
      id: null,
      title: '',
      description: '',
      image: '',
      price: 0,
      aff_link: '',
      status: '1'
    }
  ]
});

const loading = ref(false);
const error = ref(null);
const editorContainer = ref(null);
const imagePreview = ref(null);
const selectedFileName = ref('');
const uploadingImage = ref(false);
let editor = null;

const isEdit = computed(() => !!route.params.id);

const initEditor = async () => {
  if (!editorContainer.value) return;

  // Parse existing description if it's JSON
  let initialData = { blocks: [] };
  if (form.description) {
    try {
      initialData = JSON.parse(form.description);
    } catch (e) {
      // If not JSON, treat as plain text
      initialData = {
        blocks: [
          {
            type: 'paragraph',
            data: { text: form.description }
          }
        ]
      };
    }
  }

  editor = new EditorJS({
    holder: editorContainer.value,
    data: initialData,
    tools: {
      header: {
        class: Header,
        config: {
          levels: [1, 2, 3, 4, 5, 6],
          defaultLevel: 2
        }
      },
      list: {
        class: List,
        inlineToolbar: true
      },
      paragraph: {
        class: Paragraph,
        inlineToolbar: true
      },
      quote: Quote,
      code: Code,
      embed: Embed,
      table: Table,
      linkTool: LinkTool,
      image: {
        class: ImageTool,
        config: getEditorImageConfig()
      }
    },
    placeholder: 'Write your article description here...'
  });
};

const handleImageUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  selectedFileName.value = file.name;
  uploadingImage.value = true;

  try {
    // Upload using composable
    const res = await uploadImage(file);

    // Set the uploaded image URL
    form.image = res.url;
    imagePreview.value = res.url;
  } catch (e) {
    console.error('Image upload failed:', e);
    error.value = 'Failed to upload image. Please try again.';
    
    // Fallback: Create local preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } finally {
    uploadingImage.value = false;
  }
};

const handleProductImageUpload = async (event, productIndex) => {
  const file = event.target.files[0];
  if (!file) return;

  try {
    // Upload using composable
    const res = await uploadImage(file);

    // Store filename for database
    form.products[productIndex].image = res.url;
    
    // Build full URL for preview
    const fullImageUrl = window.location.origin + '/storage/articles/' + res.url;
    form.products[productIndex].imagePreview = fullImageUrl;
  } catch (e) {
    console.error('Product image upload failed:', e);
    error.value = 'Failed to upload product image. Please try again.';
  }
};

const addProduct = () => {
  form.products.push({
    id: null,
    title: '',
    description: '',
    image: '',
    imagePreview: '',
    price: 0,
    aff_link: '',
    status: '1'
  });
};

const removeProductImage = (index) => {
  form.products[index].image = '';
  form.products[index].imagePreview = '';
};

const removeProduct = (index) => {
  if (form.products.length > 1) {
    form.products.splice(index, 1);
  }
};

const loadArticle = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await articleService.getById(route.params.id);
    const article = response.data.data;

    form.title = article.title;
    form.slug = article.slug;
    form.image = article.image;
    form.description = article.description;
    form.status = String(article.status);
    form.meta_title = article.meta_title || '';
    form.meta_keywords = article.meta_keywords || '';
    form.meta_description = article.meta_description || '';

    // Load products
    if (article.products && article.products.length > 0) {
      form.products = article.products.map(p => ({
        id: p.id,
        title: p.title,
        description: p.description,
        image: p.image, // Store filename for database
        imagePreview: p.image_url, // Store full URL for preview
        price: p.price,
        aff_link: p.aff_link,
        status: String(p.status)
      }));
    }

    if (article.image_url) {
      imagePreview.value = article.image_url;
    }

    // Initialize editor after form is populated
    await initEditor();
  } catch (e) {
    console.error('Failed to load article:', e);
    error.value = 'Failed to load article. Please try again.';
  } finally {
    loading.value = false;
  }
};

const handleSubmit = async () => {
  loading.value = true;
  error.value = null;

  try {
    // Save editor content as JSON
    if (editor) {
      const editorData = await editor.save();
      form.description = JSON.stringify(editorData);
    }

    // Filter out empty products (must have at least title)
    const validProducts = form.products.filter(p => p.title && p.title.trim());

    const payload = {
      ...form,
      products: validProducts
    };

    if (isEdit.value) {
      await articleService.update(route.params.id, payload);
    } else {
      await articleService.create(payload);
    }

    router.push('/admin/articles');
  } catch (e) {
    console.error('Failed to save article:', e);
    error.value = e.response?.data?.message || 'Failed to save article. Please check all fields.';
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  if (isEdit.value) {
    await loadArticle();
  } else {
    await initEditor();
  }
});

onBeforeUnmount(() => {
  if (editor) {
    editor.destroy();
    editor = null;
  }
});
</script>
