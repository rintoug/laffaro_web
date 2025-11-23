# Image Upload System

## Overview
This application uses a centralized image upload system that organizes images into separate folders based on the module they belong to.

## Directory Structure
Images are stored in the following directories:
```
storage/app/public/
├── products/       # Product images (featured & editor)
├── categories/     # Category images
├── gift-types/     # Gift type images
├── articles/       # Article images
├── blog/          # Blog post images
├── editor/        # General editor images (default)
└── general/       # General purpose images
```

## API Endpoints

### 1. Standard Image Upload
**Endpoint:** `POST /api/admin/upload-image`

**Request:**
```javascript
const formData = new FormData();
formData.append('image', file);
formData.append('folder', 'products'); // Optional, defaults to 'general'
```

**Response:**
```json
{
  "success": true,
  "url": "http://localhost:8000/storage/products/1732123456_abc123xyz.jpg",
  "path": "products/1732123456_abc123xyz.jpg",
  "folder": "products"
}
```

### 2. EditorJS Image Upload
**Endpoint:** `POST /api/admin/editor-upload-image`

**Request:**
```javascript
const formData = new FormData();
formData.append('image', file);
formData.append('folder', 'products'); // Optional, defaults to 'editor'
```

**Response (EditorJS format):**
```json
{
  "success": 1,
  "file": {
    "url": "http://localhost:8000/storage/products/1732123456_abc123xyz.jpg"
  }
}
```

## Usage

### Using the Composable (Recommended)

```javascript
import { useImageUpload } from '@/composables/useImageUpload';

// Initialize with folder name
const { uploadImage, getEditorImageConfig } = useImageUpload('products');

// Upload a featured image
const handleImageUpload = async (file) => {
  try {
    const result = await uploadImage(file);
    console.log(result.url); // Image URL
  } catch (error) {
    console.error('Upload failed:', error);
  }
};

// Configure EditorJS Image tool
import ImageTool from '@editorjs/image';

const editor = new EditorJS({
  tools: {
    image: {
      class: ImageTool,
      config: getEditorImageConfig() // Automatically configured with folder
    }
  }
});
```

### Direct API Call

```javascript
import axios from 'axios';

const uploadImage = async (file, folder = 'general') => {
  const formData = new FormData();
  formData.append('image', file);
  formData.append('folder', folder);

  const response = await axios.post('/api/admin/upload-image', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  });

  return response.data;
};
```

## Image Validation
- **Allowed formats:** JPEG, PNG, JPG, GIF, WEBP
- **Maximum file size:** 5MB (5120KB)
- **File naming:** Timestamp + random string + extension (e.g., `1732123456_abc123xyz.jpg`)

## Controller: ImageUploadController

Located at: `app/Http/Controllers/Admin/ImageUploadController.php`

### Methods:

1. **upload(Request $request)**
   - General purpose image upload
   - Returns standard format with success, url, path, and folder

2. **uploadForEditor(Request $request)**
   - Specifically for EditorJS integration
   - Returns EditorJS expected format

## Security
- All routes are protected by `auth:api` and `admin` middleware
- Only admin users can upload images
- Images are validated for type and size before upload
- Filenames are generated randomly to prevent overwriting

## Storage Link
Make sure the storage link is created:
```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

## Example: Product Form

```vue
<script setup>
import { useImageUpload } from '@/composables/useImageUpload';
import ImageTool from '@editorjs/image';
import EditorJS from '@editorjs/editorjs';

// Initialize composable with 'products' folder
const { uploadImage, getEditorImageConfig } = useImageUpload('products');

// Featured image upload
const handleFeaturedImage = async (event) => {
  const file = event.target.files[0];
  const result = await uploadImage(file);
  form.image = result.url; // Store URL in form
};

// EditorJS initialization
const editor = new EditorJS({
  tools: {
    image: {
      class: ImageTool,
      config: getEditorImageConfig() // Images go to storage/app/public/products/
    }
  }
});
</script>
```

## Benefits
1. **Organized Storage:** Images are separated by module for easy management
2. **Reusable Code:** Single composable for all image uploads
3. **Type Safety:** Folder names are validated in the backend
4. **Consistent API:** Same endpoints for all modules
5. **EditorJS Compatible:** Special endpoint returns EditorJS expected format
