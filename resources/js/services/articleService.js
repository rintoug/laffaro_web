import axios from '../axios';

const articleService = {
  getAll(params = {}) {
    return axios.get('/admin/gift-articles', { params });
  },

  getById(id) {
    return axios.get(`/admin/gift-articles/${id}`);
  },

  create(data) {
    return axios.post('/admin/gift-articles', data);
  },

  update(id, data) {
    return axios.put(`/admin/gift-articles/${id}`, data);
  },

  delete(id) {
    return axios.delete(`/admin/gift-articles/${id}`);
  }
};

export default articleService;
