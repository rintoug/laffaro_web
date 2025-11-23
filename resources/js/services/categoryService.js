import axios from '../axios';

export default {
  list(params = {}) {
    return axios.get('/admin/categories', { params });
  },
  
  get(id) {
    return axios.get(`/admin/categories/${id}`);
  },
  
  create(data) {
    return axios.post('/admin/categories', data);
  },
  
  update(id, data) {
    return axios.put(`/admin/categories/${id}`, data);
  },
  
  destroy(id) {
    return axios.delete(`/admin/categories/${id}`);
  }
};
