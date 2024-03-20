let OkPopup = function (popups, options = {}) {
    this.popups = popups;
    this.popup_elements = [];
    this.options = options;
    this.init();
};

OkPopup.prototype.checkAndHideBackdrop = function() {
    if(this.popup_elements.filter((elem) => elem.classList.contains('show')).length <= 0) {
        this.container.style.display = 'none';
    }
};

OkPopup.prototype.drawPopup = function(popup) {
    const popupId = 'ok-popup-' + popup.id;
    const wrapper = document.createElement('div');
    const content = document.createElement('div');
    const actions = document.createElement('div');
    const dontShowBtn = document.createElement('button');
    const closeBtn = document.createElement('button');
    const curDate = new Date().setHours(0,0,0,0);
    const that = this;

    wrapper.classList.add('ok-popup', 'ok-popup-layer');
    content.classList.add('ok-popup-layer--content');
    actions.classList.add('ok-popup-layer--actions');

    content.innerHTML = popup.content;
    dontShowBtn.classList.add('ok-popup-btn');
    dontShowBtn.innerText = this.options.showOnceText || '오늘 하루동안 보지 않기';

    closeBtn.classList.add('ok-popup-btn');
    closeBtn.innerText = this.options.closeText || '닫기';

    actions.appendChild(dontShowBtn);
    actions.appendChild(closeBtn);

    const pos_x = popup.position_x,
          pos_y = popup.position_y,
          order = Number(popup.order);

    wrapper.setAttribute('id', popupId);
    wrapper.style.width = popup.width;
    wrapper.style.height = popup.height;
    wrapper.style.zIndex = 99991 + order;
    wrapper.style.left = pos_x;
    wrapper.style.top = pos_y;
    wrapper.style.transform = `translateX(-${pos_x}) translateY(-${pos_y})`;
    wrapper.style.webkitTransform = `translateX(-${pos_x}) translateY(-${pos_y})`;

    if(!localStorage.getItem(popupId)) {
        wrapper.style.display = 'block';
        wrapper.classList.add('show');
    } else {
        const date = localStorage.getItem(popupId);

        if(date != curDate.toString()) {
            localStorage.removeItem(popupId);
            wrapper.style.display = 'block';
            wrapper.classList.add('show');
        }
    }

    dontShowBtn.addEventListener('click', function() {
        localStorage.setItem(popupId, curDate.toString());
        wrapper.style.display = 'none';
        wrapper.classList.remove('show');
        that.checkAndHideBackdrop();
    });

    closeBtn.addEventListener('click', function() {
        wrapper.style.display = 'none';
        wrapper.classList.remove('show');
        that.checkAndHideBackdrop();
    });

    wrapper.appendChild(content);
    wrapper.appendChild(actions);

    this.popup_elements.push(wrapper);
    return wrapper;
};

OkPopup.prototype.init = function() {
    if(Array.isArray(this.popups) && this.popups.length > 0) {
        const container = document.createElement('div');
        container.classList.add('ok-popup-overlay');
        this.container = container;

        for (let popup of this.popups) {
            container.appendChild(this.drawPopup(popup));
        }

        if(this.popup_elements.filter((elem) => elem.classList.contains('show')).length > 0) {
            container.style.display = 'block';
        }

        document.body.appendChild(container);
    }
};
