window.$ = window.jQuery = require('jquery');

global.Popper = window.Popper = require('@popperjs/core');
window.bootstrap = require('bootstrap')
window.Vue       = require('vue');

global.Popper = window.OnScreen = require('onscreen');
global.noUiSlider = window.noUiSlider = require('nouislider');
global.SmoothScroll = window.SmoothScroll = require('smooth-scroll');

window.DatePicker = require('vanillajs-datepicker');
window.Swal = require('sweetalert2');
window.moment = require('moment');
window.Notyf = new (require('notyf')).Notyf({duration: 3500});
window.SimpleBar = require('simplebar');
window.TomSelect = require('tom-select');
window.SimpleMde = require('simplemde/dist/simplemde.min.js');
window.axios     = require('./axios');
window.Utils     = require('./utils');

require('chartist');
require('chartist-plugin-tooltips');
