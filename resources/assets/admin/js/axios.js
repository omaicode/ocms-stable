const axios = require('axios');

axios.create({
    headers: {
        'X-Requested-With': XMLHttpRequest,
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }
})

module.exports = axios