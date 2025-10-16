import axios from 'axios';
import 'bootstrap';
import '../scss/app.scss'
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
