import {createApp} from 'vue';

import App from './components/App.vue';
import Tree from './components/Tree';

const el = document.getElementById('partialEditor');

const partialApp = createApp(App, {trans: JSON.parse(el.getAttribute('data-translates')), preview_url: el.getAttribute('data-preview-url')})
partialApp.component("Tree", Tree);
partialApp.mount('#partialEditor');
