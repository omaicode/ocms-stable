import {createApp} from 'vue';
import {container} from "jenesius-vue-modal";

import App from './components/media/app.js';
import TabDisplay from './components/media/tab-display';
import TabGrid from './components/media/tab-grid';
import FormButton from './components/media/form-button';

const media = createApp({});

media.component('App', App);
media.component('TabDisplay', TabDisplay);
media.component('TabGrid', TabGrid);
media.component('MediaFormButton', FormButton);
media.component('ModalContainer', container);
media.mount('#ocms-media-app');