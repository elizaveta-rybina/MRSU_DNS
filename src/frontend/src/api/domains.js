import axios from 'axios';
import { useQuery, useMutation, useQueryClient } from 'react-query';
import { createAsyncThunk } from '@reduxjs/toolkit';

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

// Получить все домены
const fetchDomains = async () => {
  const response = await api.get('/domains');
  return response.data;
};

// Получить домен по ID
const fetchDomainById = async (domainId) => {
  const response = await api.get(`/domains/${domainId}`);
  return response.data;
};

// Создать новый домен
const createDomain = async (domain) => {
  const response = await api.post('/domains', domain);
  return response.data;
};

// Обновить домен по ID
const updateDomain = async ({ domainId, domain }) => {
  const response = await api.put(`/domains/${domainId}`, domain);
  return response.data;
};

// Удалить домен по ID
const deleteDomain = async (domainId) => {
  const response = await api.delete(`/domains/${domainId}`);
  return response.data;
};

// Custom hooks
export const useDomains = () => useQuery('domains', fetchDomains);
export const useDomain = (domainId) => useQuery(['domain', domainId], () => fetchDomainById(domainId));
export const useCreateDomain = () => {
  const queryClient = useQueryClient();
  return useMutation(createDomain, {
    onSuccess: () => {
      queryClient.invalidateQueries('domains');
    },
  });
};
export const useUpdateDomain = () => {
  const queryClient = useQueryClient();
  return useMutation(({ domainId, domain }) => updateDomain({ domainId, domain }), {
    onSuccess: () => {
      queryClient.invalidateQueries('domains');
    },
  });
};
export const useDeleteDomain = () => {
  const queryClient = useQueryClient();
  return useMutation(deleteDomain, {
    onSuccess: () => {
      queryClient.invalidateQueries('domains');
    },
  });
};

export const fetchDomainsThunk = createAsyncThunk('domains/fetchAll', async () => {
  const response = await fetchDomains();
  return response;
});
