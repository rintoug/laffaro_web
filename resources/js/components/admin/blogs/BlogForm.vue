<template>
  <div class="max-w-5xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ isEdit ? 'Edit' : 'Create' }} Blog Post</h2>
        <p class="text-gray-600 mt-1">{{ isEdit ? 'Update blog post information' : 'Add a new blog post' }}</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
          <input 
            v-model="form.title" 
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Blog post title"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
          <textarea 
            v-model="form.short_description" 
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Brief description for preview"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Content (Rich Text Editor)
            <span class="text-xs text-gray-500 font-normal ml-2">Use the + button to add blocks (text, images, code, HTML, etc.)</span>
          </label>
          <div 
            ref="editorContainer" 
            class="border-2 border-gray-300 rounded-lg min-h-[400px] bg-white shadow-sm hover:border-gray-400 transition-colors focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200"
          ></div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
          
          <!-- Image Preview -->
          <div v-if="imagePreview || form.image_url" class="mb-4 relative inline-block">
            <img 
              :src="imagePreview || form.image_url" 
              alt="Blog preview" 
              class="max-w-md w-full h-auto rounded-lg border-2 border-gray-300 shadow-sm"
            />
            <button 
              type="button"
              @click="removeImage"
              class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition-colors"
              title="Remove Image"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- File Upload -->
          <div v-if="!imagePreview && !form.image_url" class="flex items-center gap-4">
            <label class="flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg cursor-pointer transition-colors shadow-sm">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="text-sm font-medium">Choose Image</span>
              <input 
                type="file" 
                @change="handleImageUpload" 
                accept="image/*"
                class="hidden"
              />
            </label>
            <span v-if="uploadingImage" class="text-sm text-gray-600">Uploading...</span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
          <input 
            v-model="form.meta_title" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="SEO title"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
          <textarea 
            v-model="form.meta_keywords" 
            rows="2"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="keyword1, keyword2, keyword3"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
          <textarea 
            v-model="form.meta_description" 
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="SEO description for search engines"
          ></textarea>
        </div>

        <div>
          <label class="flex items-center gap-2">
            <input 
              type="checkbox" 
              v-model="form.status" 
              true-value="1" 
              false-value="0"
              class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
            />
            <span class="text-sm font-medium text-gray-700">Published</span>
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
            {{ loading ? 'Saving...' : 'Save Blog Post' }}
          </button>
          <router-link 
            to="/admin/blogs" 
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
import blogService from '../../../services/blogService';
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

// Use image upload composable with 'blog' folder
const { uploadImage, getEditorImageConfig } = useImageUpload('blog');

const form = reactive({
  title: '',
  image: '',
  image_url: null,
  author: '',
  short_description: '',
  description: '',
  status: '1',
  meta_title: '',
  meta_keywords: '',
  meta_description: ''
});

const loading = ref(false);
const error = ref(null);
const editorContainer = ref(null);
const imagePreview = ref(null);
const selectedFileName = ref('');
const uploadingImage = ref(false);
const originalImage = ref(''); // Store original image URL for deletion
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
    placeholder: 'Write your blog content here...',
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
    // Use originalImage (loaded from backend) instead of form.image
    console.log('=== UPLOAD DEBUG ===');
    console.log('originalImage.value:', originalImage.value);
    console.log('form.image:', form.image);
    console.log('form.image_url:', form.image_url);
    
    // Construct old image URL from form.image if other values are empty
    let oldImage = originalImage.value || form.image_url || '';
    if (!oldImage && form.image) {
      oldImage = window.location.origin + '/storage/blog/' + form.image;
      console.log('Constructed oldImage from form.image:', oldImage);
    }
    console.log('Using oldImage:', oldImage);
    const res = await uploadImage(file, '', oldImage);
    
    // Save only the filename to form.image (for database)
    form.image = res.url;
    
    // Build full URL for preview
    const fullImageUrl = window.location.origin + '/storage/blog/' + res.url;
    imagePreview.value = fullImageUrl;
    
    // Update originalImage to the new image URL for subsequent uploads
    originalImage.value = fullImageUrl;
  } catch (e) {
    console.error('Image upload failed:', e);
    error.value = 'Failed to upload image. Please try again.';
    
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
  form.image_url = null;
  imagePreview.value = null;
  selectedFileName.value = '';
  originalImage.value = '';
};

const load = async () => {
  loading.value = true;
  try {
    const res = await blogService.get(route.params.id);
    console.log('=== BLOG LOAD DEBUG ===');
    console.log('Response data:', res.data.data);
    console.log('Image URL from response:', res.data.data.image_url);
    
    Object.assign(form, res.data.data);
    
    console.log('After assign - form.image_url:', form.image_url);
    console.log('After assign - form.image:', form.image);
    
    // If image_url is not set but we have image filename, construct the URL
    if (!form.image_url && form.image) {
      form.image_url = window.location.origin + '/storage/blog/' + form.image;
      console.log('Constructed image_url from filename:', form.image_url);
    }
    
    await initEditor();
    
    if (form.image_url) {
      imagePreview.value = form.image_url;
      originalImage.value = form.image_url; // Store for deletion on new upload
      console.log('Set originalImage.value to:', originalImage.value);
    } else {
      console.log('WARNING: form.image_url is empty!');
    }
  } catch (e) {
    error.value = 'Failed to load blog post';
    console.error('Load error:', e);
  } finally {
    loading.value = false;
  }
};

const submit = async () => {
  error.value = null;
  loading.value = true;
  
  try {
    if (editor) {
      const outputData = await editor.save();
      form.description = JSON.stringify(outputData);
    }

    if (isEdit.value) {
      await blogService.update(route.params.id, form);
    } else {
      await blogService.create(form);
    }
    router.push({ name: 'Blogs' });
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed';
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  if (isEdit.value) {
    await load();
  } else {
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
