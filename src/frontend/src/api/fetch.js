import axios from 'axios';
import { useMutation } from 'react-query';

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

// Функция аутентификации пользователя
const authenticateUser = async (credentials) => {
  const response = await api.post('/login', credentials);
  return response.data;
};

// Хук для использования в компоненте
export const useAuthenticateUser = () => {
  return useMutation(authenticateUser);
};
