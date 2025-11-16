import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// JANGAN import Alpine.js di sini, kita akan gunakan dari CDN