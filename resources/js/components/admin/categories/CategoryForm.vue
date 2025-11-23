<template>
  <div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ isEdit ? 'Edit' : 'Create' }} Category</h2>
        <p class="text-gray-600 mt-1">{{ isEdit ? 'Update category information' : 'Add a new category to your store' }}</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
            <input 
              v-model="form.name" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="Category name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
            <input 
              v-model="form.slug" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="category-slug (auto-generated)"
            />
            <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate from name</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
          <textarea 
            v-model="form.description" 
            rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Category description"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
          
          <!-- Image Preview -->
          <div v-if="imagePreview" class="mb-4">
            <img 
              :src="imagePreview" 
              alt="Category preview" 
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
            {{ loading ? 'Saving...' : 'Save Category' }}
          </button>
          <router-link 
            to="/admin/categories" 
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
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import categoryService from '../../../services/categoryService';
import { useImageUpload } from '../../../composables/useImageUpload';

const router = useRouter();
const route = useRoute();

// Use image upload composable with 'categories' folder
const { uploadImage } = useImageUpload('categories');

const form = reactive({
  name: '',
  slug: '',
  description: '',
  image: '',
  status: '1',
  meta_title: '',
  meta_keywords: '',
  meta_description: ''
});

const loading = ref(false);
const error = ref(null);
const imagePreview = ref(null);
const originalImage = ref('');
const selectedFileName = ref('');
const uploadingImage = ref(false);

const isEdit = computed(() => !!route.params.id);

const handleImageUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  selectedFileName.value = file.name;
  uploadingImage.value = true;

  try {
    const oldImage = originalImage.value || '';
    const res = await uploadImage(file, form.name, oldImage);

    // Save only the filename to form.image (for database)
    form.image = res.url;
    
    // Build full URL for preview
    const fullImageUrl = window.location.origin + '/storage/categories/' + res.url;
    imagePreview.value = fullImageUrl;
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

const load = async () => {
  loading.value = true;
  try {
    const res = await categoryService.get(route.params.id);
    const category = res.data.data;
    Object.assign(form, category);
    
    // Set image preview using the accessor URL
    if (category.image_url) {
      imagePreview.value = category.image_url;
      originalImage.value = category.image_url;
    }
  } catch (e) {
    error.value = 'Failed to load category';
  } finally {
    loading.value = false;
  }
};

const submit = async () => {
  error.value = null;
  loading.value = true;
  try {
    if (isEdit.value) {
      await categoryService.update(route.params.id, form);
    } else {
      await categoryService.create(form);
    }
    router.push({ name: 'Categories' });
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  if (isEdit.value) load();
});
</script>
