<template>
  <div class="max-w-5xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ isEdit ? 'Edit' : 'Create' }} Product</h2>
        <p class="text-gray-600 mt-1">{{ isEdit ? 'Update product information' : 'Add a new product to your store' }}</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
            <input 
              v-model="form.title" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="Product title"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
            <input 
              v-model="form.price" 
              type="number" 
              step="0.01" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="0.00"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
            <div class="border border-gray-300 rounded-lg p-3 max-h-48 overflow-y-auto">
              <div v-if="categories.length === 0" class="text-sm text-gray-500">Loading categories...</div>
              <label v-for="category in categories" :key="category.id" class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 px-2 rounded">
                <input 
                  type="checkbox" 
                  :value="category.id"
                  v-model="selectedCategories"
                  class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <span class="text-sm text-gray-700">{{ category.name }}</span>
              </label>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gift Types</label>
            <div class="border border-gray-300 rounded-lg p-3 max-h-48 overflow-y-auto">
              <div v-if="giftTypes.length === 0" class="text-sm text-gray-500">Loading gift types...</div>
              <label v-for="giftType in giftTypes" :key="giftType.id" class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 px-2 rounded">
                <input 
                  type="checkbox" 
                  :value="giftType.id"
                  v-model="selectedGiftTypes"
                  class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <span class="text-sm text-gray-700">{{ giftType.name }}</span>
              </label>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
          <textarea 
            v-model="form.short_description" 
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Brief product description"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Description (Rich Text Editor)
            <span class="text-xs text-gray-500 font-normal ml-2">Use the + button to add blocks (text, images, code, HTML, etc.)</span>
          </label>
          <div 
            ref="editorContainer" 
            class="border-2 border-gray-300 rounded-lg min-h-[400px] bg-white shadow-sm hover:border-gray-400 transition-colors focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200"
          ></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
          
          <!-- Image Preview -->
          <div v-if="imagePreview" class="mb-4">
            <img 
              :src="imagePreview" 
              alt="Product preview" 
              class="w-48 h-48 object-cover rounded-lg border border-gray-300"
            />
            <button 
              type="button"
              @click="removeImage"
              class="mt-2 text-sm text-red-600 hover:text-red-800"
            >
              Remove Image
            </button>
          </div>

          <!-- File Upload -->
          <div class="flex items-center gap-4">
            <label class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
              <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="text-sm font-medium text-gray-700">Choose Image</span>
              <input 
                type="file" 
                @change="handleImageUpload" 
                accept="image/*"
                class="hidden"
              />
            </label>
            <span v-if="uploadingImage" class="text-sm text-gray-600">Uploading...</span>
            <span v-if="selectedFileName" class="text-sm text-gray-600">{{ selectedFileName }}</span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Affiliate Link</label>
          <input 
            v-model="form.aff_link" 
            type="url"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="https://affiliate-link.com"
          />
        </div>

        <div class="border-t border-gray-200 pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
              <input 
                v-model="form.meta_title" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="SEO meta title"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
              <input 
                v-model="form.meta_keywords" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="keyword1, keyword2, keyword3"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
              <textarea 
                v-model="form.meta_description" 
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="SEO meta description"
              ></textarea>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
          <label class="flex items-center gap-2 cursor-pointer">
            <input 
              type="checkbox" 
              v-model="form.status" 
              true-value="1" 
              false-value="0"
              class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
            />
            <span class="text-sm font-medium text-gray-700">Active</span>
          </label>
        </div>

        <div v-if="error" class="p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-sm text-red-800">{{ error }}</p>
        </div>

        <div class="flex gap-3 pt-4">
          <button 
            type="submit" 
            :disabled="loading"
            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
          >
            {{ loading ? 'Saving...' : 'Save Product' }}
          </button>
          <router-link 
            to="/admin/products" 
            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors"
          >
            Cancel
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import productService from '../../../services/productService';
import categoryService from '../../../services/categoryService';
import giftTypeService from '../../../services/giftTypeService';
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
import RawTool from '@editorjs/raw';

const router = useRouter();
const route = useRoute();

// Use image upload composable with 'products' folder
const { uploadImage, getEditorImageConfig } = useImageUpload('products');

const form = reactive({
  title: '',
  image: '',
  short_description: '',
  description: '',
  aff_link: '',
  price: 0,
  status: '1',
  meta_title: '',
  meta_keywords: '',
  meta_description: ''
});

const loading = ref(false);
const error = ref(null);
const editorContainer = ref(null);
const imagePreview = ref(null);
const originalImage = ref(''); // Store original image URL for deletion
const selectedFileName = ref('');
const uploadingImage = ref(false);
const categories = ref([]);
const giftTypes = ref([]);
const selectedCategories = ref([]);
const selectedGiftTypes = ref([]);
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
        },
        inlineToolbar: true
      },
      list: {
        class: List,
        inlineToolbar: true
      },
      paragraph: {
        class: Paragraph,
        inlineToolbar: true
      },
      quote: {
        class: Quote,
        inlineToolbar: true
      },
      code: {
        class: Code
      },
      raw: {
        class: RawTool,
        inlineToolbar: false
      },
      embed: {
        class: Embed,
        config: {
          services: {
            youtube: true,
            vimeo: true,
            twitter: true,
            instagram: true,
            codepen: true,
            pinterest: true
          }
        }
      },
      table: {
        class: Table,
        inlineToolbar: true
      },
      linkTool: {
        class: LinkTool,
        config: {
          endpoint: '/api/admin/fetch-url'
        }
      },
      image: {
        class: ImageTool,
        config: getEditorImageConfig()
      }
    },
    placeholder: 'Write your product description here...',
    autofocus: false,
    minHeight: 400
  });
};

const handleImageUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  selectedFileName.value = file.name;
  uploadingImage.value = true;

  try {
    // Upload using composable with product title and old image
    // Use originalImage (loaded from backend) instead of form.image (which may be empty)
    const oldImage = originalImage.value || '';
    console.log('Uploading with oldImage:', oldImage);
    const res = await uploadImage(file, form.title, oldImage);

    // Save only the filename to form.image (for database)
    form.image = res.url;
    
    // Build full URL for preview (products folder has original and medium subdirectories)
    const fullImageUrl = window.location.origin + '/storage/products/original/' + res.url;
    imagePreview.value = fullImageUrl;
    
    // Update originalImage to the new image URL for subsequent uploads
    originalImage.value = fullImageUrl;
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

const removeImage = () => {
  form.image = '';
  imagePreview.value = null;
  selectedFileName.value = '';
};

const loadCategories = async () => {
  try {
    const res = await categoryService.list({ per_page: 100 });
    categories.value = res.data.data.data || [];
  } catch (e) {
    console.error('Failed to load categories:', e);
  }
};

const loadGiftTypes = async () => {
  try {
    const res = await giftTypeService.list({ per_page: 100 });
    giftTypes.value = res.data.data.data || [];
  } catch (e) {
    console.error('Failed to load gift types:', e);
  }
};

const load = async () => {
  loading.value = true;
  try {
    const res = await productService.get(route.params.id);
    const product = res.data.data;
    Object.assign(form, product);
    
    // Load selected categories and gift types
    if (product.categories) {
      selectedCategories.value = product.categories.map(c => c.id);
    }
    if (product.gift_types) {
      selectedGiftTypes.value = product.gift_types.map(g => g.id);
    }
    
    // Initialize editor after form data is loaded
    await initEditor();
    
    // Set image preview using the accessor URL and store original image URL for deletion
    if (product.image_url) {
      imagePreview.value = product.image_url;
      originalImage.value = product.image_url; // Store for deletion on new upload
    }
  } catch (e) {
    error.value = 'Failed to load product';
  } finally {
    loading.value = false;
  }
};

const submit = async () => {
  error.value = null;
  loading.value = true;
  
  try {
    // Save editor content
    if (editor) {
      const outputData = await editor.save();
      form.description = JSON.stringify(outputData);
    }

    // Prepare data with categories and gift types
    const data = {
      ...form,
      categories: selectedCategories.value,
      gift_types: selectedGiftTypes.value
    };

    if (isEdit.value) {
      await productService.update(route.params.id, data);
    } else {
      await productService.create(data);
    }
    router.push({ name: 'Products' });
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed';
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  // Load categories and gift types
  await Promise.all([loadCategories(), loadGiftTypes()]);
  
  if (isEdit.value) {
    await load();
  } else {
    // Initialize editor for new product
    await initEditor();
  }
});

onBeforeUnmount(() => {
  if (editor && editor.destroy) {
    editor.destroy();
  }
});
</script>

<style>
/* EditorJS Enhanced Styles */
.ce-block__content,
.ce-toolbar__content {
  max-width: 100%;
}

.codex-editor {
  padding: 1.5rem;
  min-height: 400px;
  background: #ffffff;
}

.codex-editor__redactor {
  padding-bottom: 2rem !important;
}

.ce-toolbar__actions {
  right: 0;
}

/* Toolbar styling */
.ce-toolbar__plus,
.ce-toolbar__settings-btn {
  color: #6366f1 !important;
  background: #f3f4f6;
  border-radius: 0.375rem;
  transition: all 0.2s;
}

.ce-toolbar__plus:hover,
.ce-toolbar__settings-btn:hover {
  background: #e5e7eb;
  color: #4f46e5 !important;
}

/* Block styling */
.ce-block {
  padding: 0.5rem 0;
}

.ce-block--selected .ce-block__content {
  background: #f9fafb;
  border-radius: 0.375rem;
  padding: 0.5rem;
}

/* Paragraph styling */
.ce-paragraph {
  line-height: 1.75;
  font-size: 1rem;
  color: #374151;
}

/* Header styling */
.ce-header {
  color: #111827;
  font-weight: 700;
  line-height: 1.3;
  margin: 1rem 0;
}

/* Code block styling */
.ce-code__textarea {
  background: #1f2937 !important;
  color: #f3f4f6 !important;
  font-family: 'Monaco', 'Courier New', monospace;
  border-radius: 0.5rem;
  padding: 1rem !important;
  min-height: 150px;
  font-size: 0.875rem;
  line-height: 1.6;
  border: none !important;
}

/* Quote styling */
.cdx-quote__text {
  font-size: 1.125rem;
  line-height: 1.75;
  color: #4b5563;
  font-style: italic;
}

.cdx-quote__caption {
  color: #6b7280;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

/* List styling */
.cdx-list__item {
  line-height: 1.75;
  padding: 0.375rem 0;
  color: #374151;
}

/* Table styling */
.tc-table {
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid #e5e7eb;
}

.tc-table__cell {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
}

/* Image styling */
.cdx-block.image-tool {
  margin: 1.5rem 0;
}

.image-tool__image {
  border-radius: 0.5rem;
  overflow: hidden;
}

.image-tool__caption {
  margin-top: 0.5rem;
  padding: 0.5rem;
  background: #f9fafb;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  color: #6b7280;
}

/* Raw HTML tool styling */
.ce-rawtool__textarea {
  background: #fef3c7 !important;
  border: 2px dashed #f59e0b !important;
  border-radius: 0.5rem;
  padding: 1rem !important;
  font-family: 'Monaco', 'Courier New', monospace;
  font-size: 0.875rem;
  min-height: 150px;
  color: #92400e;
}

/* Embed styling */
.embed-tool__caption {
  margin-top: 0.5rem;
  font-size: 0.875rem;
  color: #6b7280;
}

/* Inline toolbar */
.ce-inline-toolbar {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  border-radius: 0.5rem;
}

.ce-inline-tool,
.ce-conversion-tool {
  color: #4b5563;
  transition: all 0.2s;
}

.ce-inline-tool:hover,
.ce-conversion-tool:hover {
  background: #f3f4f6;
  color: #111827;
}

/* Settings panel */
.ce-settings {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-radius: 0.5rem;
}

.ce-settings__button {
  color: #4b5563;
  transition: all 0.2s;
}

.ce-settings__button:hover {
  background: #f3f4f6;
  color: #111827;
}

/* Popover */
.ce-popover {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  border-radius: 0.5rem;
}

.ce-popover-item {
  color: #4b5563;
  transition: all 0.2s;
}

.ce-popover-item:hover {
  background: #f3f4f6;
  color: #111827;
}

.ce-popover-item__icon {
  background: #f3f4f6;
  border-radius: 0.375rem;
}

/* Link tool */
.link-tool__input {
  border: 1px solid #d1d5db !important;
  border-radius: 0.375rem !important;
  padding: 0.5rem 0.75rem !important;
  font-size: 0.875rem;
}

.link-tool__input:focus {
  border-color: #6366f1 !important;
  outline: none;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Placeholder */
.ce-block .ce-block__content [contentEditable=true][data-placeholder]:empty::before {
  color: #9ca3af;
}
</style>
