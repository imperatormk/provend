import axios from 'axios'

const http = axios.create({
  baseURL: '/api'
})

// 401 response interceptor
http.interceptors.response.use(response => response, (error) => {
  if (error && error.response) {
    if (error.response.status === 401 && !window.location.href.includes('login')) {
      console.log(401)
    } else if (error.response.status === 403) {
      console.log(403)
    }
  }
  return Promise.reject(error)
})

export default http