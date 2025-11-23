import axios from '../axios';

export default {
  list(params = {}) {
    return axios.get('/admin/blogs', { params });
  },
  
  get(id) {
    return axios.get(`/admin/blogs/${id}`);
  },
  
  create(data) {
    return axios.post('/admin/blogs', data);
  },
  
  update(id, data) {
    return axios.put(`/admin/blogs/${id}`, data);
  },
  
  destroy(id) {
    return axios.delete(`/admin/blogs/${id}`);
  }
};
