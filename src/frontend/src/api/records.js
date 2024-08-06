import axios from 'axios';
import { useQuery, useMutation, useQueryClient } from 'react-query';

// Создаем экземпляр axios
const api = axios.create({
  baseURL: 'http://localhost',
});

api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Получить все записи для домена
const fetchRecords = async (domainId, type) => {
  const response = await api.get(`/domains/${domainId}/records`, { params: { type } });
  return response.data;
};

// Получить запись по ID
const fetchRecordById = async (recordId) => {
  const response = await api.get(`/records/${recordId}`);
  return response.data;
};

// Создать новую запись
const createRecord = async (record) => {
  const response = await api.post('/records', record);
  return response.data;
};

// Обновить запись по ID
const updateRecord = async ({ recordId, record }) => {
  const response = await api.put(`/records/${recordId}`, record);
  return response.data;
};

// Удалить запись по ID
const deleteRecord = async (recordId) => {
  const response = await api.delete(`/records/${recordId}`);
  return response.data;
};

// Custom hooks
export const useRecords = (domainId, type) => useQuery(['records', domainId, type], () => fetchRecords(domainId, type));
export const useRecord = (recordId) => useQuery(['record', recordId], () => fetchRecordById(recordId));
export const useCreateRecord = () => {
  const queryClient = useQueryClient();
  return useMutation(createRecord, {
    onSuccess: () => {
      queryClient.invalidateQueries(['records']);
    },
  });
};
export const useUpdateRecord = () => {
  const queryClient = useQueryClient();
  return useMutation(({ recordId, record }) => updateRecord({ recordId, record }), {
    onSuccess: () => {
      queryClient.invalidateQueries(['records']);
    },
  });
};
export const useDeleteRecord = () => {
  const queryClient = useQueryClient();
  return useMutation(deleteRecord, {
    onSuccess: () => {
      queryClient.invalidateQueries(['records']);
    },
  });
};
