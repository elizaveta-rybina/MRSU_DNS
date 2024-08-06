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

// Получить всех пользователей
const fetchUsers = async () => {
  const response = await api.get('/users');
  return response.data;
};

// Получить пользователя по ID
const fetchUserById = async (userId) => {
  const response = await api.get(`/users/${userId}`);
  return response.data;
};

// Создать нового пользователя
const createUser = async (user) => {
  const response = await api.post('/users', user);
  return response.data;
};

// Обновить пользователя по ID
const updateUser = async ({ userId, user }) => {
  const response = await api.put(`/users/${userId}`, user);
  return response.data;
};

// Удалить пользователя по ID
const deleteUser = async (userId) => {
  const response = await api.delete(`/users/${userId}`);
  return response.data;
};

// Custom hooks
export const useUsers = () => useQuery('users', fetchUsers);
export const useUser = (userId) => useQuery(['user', userId], () => fetchUserById(userId));
export const useCreateUser = () => {
  const queryClient = useQueryClient();
  return useMutation(createUser, {
    onSuccess: () => {
      queryClient.invalidateQueries('users');
    },
  });
};
export const useUpdateUser = () => {
  const queryClient = useQueryClient();
  return useMutation(({ userId, user }) => updateUser({ userId, user }), {
    onSuccess: () => {
      queryClient.invalidateQueries('users');
    },
  });
};
export const useDeleteUser = () => {
  const queryClient = useQueryClient();
  return useMutation(deleteUser, {
    onSuccess: () => {
      queryClient.invalidateQueries('users');
    },
  });
};
