import axios from '../axios';

/**
 * Composable for handling image uploads
 * @param {string} folder - The folder name where images should be stored (products, categories, etc.)
 */
export const useImageUpload = (folder = 'general') => {
  /**
   * Upload a single image file
   * @param {File} file - The image file to upload
   * @param {string} title - Optional title to include in filename
   * @param {string} oldImage - Optional old image URL to delete
   * @returns {Promise<Object>} - Returns { success, url, path, folder }
   */
  const uploadImage = async (file, title = '', oldImage = '') => {
    console.log('uploadImage called with:', { 
      fileName: file.name, 
      folder, 
      title, 
      oldImage 
    });
    
    const formData = new FormData();
    formData.append('image', file);
    formData.append('folder', folder);
    if (title) {
      formData.append('title', title);
    }
    if (oldImage) {
      formData.append('old_image', oldImage);
      console.log('Old image will be deleted:', oldImage);
    } else {
      console.log('No old image to delete');
    }

    try {
      const response = await axios.post('/admin/upload-image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      return response.data;
    } catch (error) {
      console.error('Image upload failed:', error);
      throw error;
    }
  };

  /**
   * Upload image for EditorJS
   * Returns EditorJS expected format: { success: 1, file: { url } }
   * @param {File} file - The image file to upload
   * @returns {Promise<Object>} - Returns EditorJS format
   */
  const uploadImageForEditor = async (file) => {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('folder', folder);

    try {
      const response = await axios.post('/admin/editor-upload-image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      return response.data;
    } catch (error) {
      console.error('EditorJS image upload failed:', error);
      throw error;
    }
  };

  /**
   * Get EditorJS Image tool configuration
   * @returns {Object} - EditorJS Image tool config
   */
  const getEditorImageConfig = () => {
    return {
      uploader: {
        /**
         * Upload file to the server and return uploaded image data
         * @param {File} file - file selected from device or pasted by drag-n-drop
         * @return {Promise.<{success, file: {url}}>}
         */
        uploadByFile(file) {
          return uploadImageForEditor(file);
        },

        /**
         * Send URL-string to the server
         * @param {string} url - pasted image URL
         * @return {Promise.<{success, file: {url}}>}
         */
        uploadByUrl(url) {
          return Promise.resolve({
            success: 1,
            file: {
              url: url
            }
          });
        }
      }
    };
  };

  return {
    uploadImage,
    uploadImageForEditor,
    getEditorImageConfig
  };
};
