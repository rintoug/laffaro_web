import axios from '../axios';

export default {
  list(params = {}) {
    return axios.get('/admin/products', { params });
  },
  get(id) {
    return axios.get(`/admin/products/${id}`);
  },
  create(payload) {
    return axios.post('/admin/products', payload);
  },
  update(id, payload) {
    return axios.put(`/admin/products/${id}`, payload);
  },
  destroy(id) {
    return axios.delete(`/admin/products/${id}`);
  }
};
