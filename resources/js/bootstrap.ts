import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Read Laravel's current XSRF-TOKEN cookie at request time. A token copied from
// the initial page HTML becomes stale whenever Laravel rotates the session.
window.axios.defaults.withXSRFToken = true;
