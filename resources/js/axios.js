import axios from "axios"

axios.defaults.withCredentials = true
// Create a reusable Axios instance
const api = axios.create({
  baseURL: "/api/v0",
  withCredentials: true,
})

// Optional: request interceptor
api.interceptors.request.use(
  (config) => {
    // You can add common headers here if needed
    return config
  },
  (error) => Promise.reject(error),
)

// Optional: response interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Handle errors globally
    return Promise.reject(error)
  },
)

export default api
