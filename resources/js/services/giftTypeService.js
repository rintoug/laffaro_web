import axios from '../axios';

export default {
  list(params = {}) {
    return axios.get('/admin/gift-types', { params });
  },
  
  get(id) {
    return axios.get(`/admin/gift-types/${id}`);
  },
  
  create(data) {
    return axios.post('/admin/gift-types', data);
  },
  
  update(id, data) {
    return axios.put(`/admin/gift-types/${id}`, data);
  },
  
  destroy(id) {
    return axios.delete(`/admin/gift-types/${id}`);
  }
};
