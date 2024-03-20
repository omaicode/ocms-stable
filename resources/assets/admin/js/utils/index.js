function Utils(el)
{
    this.el = el
}

Utils.toggleLoading = function({el, loading_text = 'Please wait...', callback}) {
    const loadingText  = loading_text ? `<span class="text-light ms-2">${loading_text}</span>` : '';
    const originalText = el.innerHTML

    if(el instanceof HTMLButtonElement !== true) {
        console.error("Element must be an instance of HTMLButtonElement.");

        return;
    }

    el.innerHTML = `<div class="d-flex align-items-center justify-content-center w-100"><div class="spinner-border spinner-border-sm text-light" role="status"></div>${loadingText}</div>`;
    el.classList.add('disabled')

    const completed = function() {
        el.innerHTML = originalText
        el.classList.remove('disabled')
    };

    if(typeof callback == 'function') {
        callback(el, completed)
    }
}

module.exports = Utils
