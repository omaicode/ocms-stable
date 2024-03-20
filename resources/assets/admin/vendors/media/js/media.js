class Media
{
    locale = 'en';
    url = '/admin/media/api';
    items = [];
    selectedItems = [];
    files = [];

    isFetching = false;
    multiple = false;
    selected = false;

    searchTimer = null;
    queryParams = {
        path: '/',
        display: 'all',
        orderBy: 'title',
        sortBy: 'asc',
        search: ''
    };
    notify = {
        show: false,
        success: true,
        message: ''
    };

    elements = {
        tableMediaList: null,
        backBtn: null,
        triggeredElement: null
    };

    text = {
        en: {
            back: 'Back'
        },

        ko: {
            back: '뒤로가기'
        }
    }

    constructor(locale = 'en', url = '/admin/media/api') {
        this.url = url;
        this.locale = locale;
        this.init();
    }

    async init() {
        this.addElements();
        this.attachEvents();
        await this.fetchData();
    }

    addElements() {
        this.elements.tableMediaList = $('#table-media-list');
        this.elements.backBtn = $(`<tr class="media-item" id="backBtn"><td colspan="3"><div class="item-title"><i class="fas fa-arrow-left"></i> ${this.text[this.locale].back}...</div></td></tr>`);
    }

    attachEvents() {
        $(document).on('click', '#backBtn', (e) => {
            const paths = this.queryParams.path.split('/');
            paths.splice(paths.length - 1, 1);
            this.queryParams.path = paths.join('/') || '/';
            this.fetchData();
        });

        $('#mediaOrderBy').on('change', (e) => {
            this.queryParams.order_by = $(e.currentTarget).val();
            this.fetchData();
        });

        $('#mediaOrderDirection').on('change', (e) => {
            this.queryParams.sortBy = $(e.currentTarget).val();
            this.fetchData();
        });

        $('#mediaSearchIpn').on('input', (e) => {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => {
                this.queryParams.search = $(e.currentTarget).val();
                this.fetchData();
            }, 350);
        });

        $(document).on('click', '[data-type="media-button"]', (e) => {
            var action = e.currentTarget.getAttribute('data-trigger'),
                modalElement = null,
                modal = null;

            switch(action) {
                case 'selectFiles':
                    $('#mediaFileInput').trigger('click');
                break;
                case 'refreshData':
                    this.fetchData();
                break;
                case 'changeDisplay':
                    $('.display-list--item').removeClass('active');
                    e.currentTarget.classList.add('active');
                    this.queryParams.display = e.currentTarget.getAttribute('data-tab-id');
                    this.fetchData();
                break;
                case 'showSelectMediaModal':
                    modalElement = document.getElementById('selectMediaModal');
                    modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    this.elements.triggeredElement = e.currentTarget;
                    modal.show();
                break;
                case 'selectMedia':
                    modalElement = document.getElementById('selectMediaModal');
                    modal = bootstrap.Modal.getOrCreateInstance(modalElement);

                    if(this.elements.triggeredElement) {
                        const input = this.elements.triggeredElement.parentNode.querySelector('input[type="hidden"]');
                        const item = this.items.find(x => x.uuid === this.selectedItems[0]);

                        if(input && item) {
                            const previewEl = this.elements.triggeredElement.parentNode.querySelector('.form-button-preview');
                            const placeholderEl = this.elements.triggeredElement.parentNode.querySelector('.form-button-placeholder');

                            if(previewEl) {
                                previewEl.style.backgroundImage = `url("${item.url}")`;
                                placeholderEl.style.display = 'none';
                            }

                            if(item.stored_db) {
                                input.value = item.uuid;
                            } else {
                                input.value = item.path;
                            }

                            input.dispatchEvent(new Event("input"));
                        }

                        modal.hide();
                    }
                break;
            }
        });

        $('#mediaAddFolderForm').on('submit', (e) => {
            e.preventDefault();
            var el = document.getElementById('mediaModalCreateFolder'),
                modal = bootstrap.Modal.getInstance(el);

            $.ajax({
                method: "POST",
                url: `/${window.OCMS.admin_prefix}/media/api/create-folder`,
                data: {path: this.queryParams.path, folder_name: $('#mediaAddFolderForm input[name="folder_name"]').val()},
                headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').prop('content')},
                success: (data) => {
                    if(data && data.success) {
                        modal.hide();
                    } else {
                        alert(data.message);
                    }
                },
                complete: () => this.fetchData()
            });
        });

        $('#mediaMoveForm').on('submit', (e) => {
            e.preventDefault();
            var el = document.getElementById('mediaModalMove'),
                modal = bootstrap.Modal.getInstance(el),
                from = this.items.filter(x => x.uuid === this.selectedItems[0]).map(x => x.path);

            $.ajax({
                method: "POST",
                url: `/${window.OCMS.admin_prefix}/media/api/move`,
                data: {from, to: $('#mediaMoveForm input[name="to"]').val()},
                headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').prop('content')},
                success: (data) => {
                    if(data && data.success) {
                        modal.hide();
                    } else {
                        alert(data.message);
                    }
                },
                complete: () => this.fetchData()
            });
        });

        $('#mediaDeleteForm').on('submit', (e) => {
            e.preventDefault();
            var el = document.getElementById('mediaModalDelete'),
                modal = bootstrap.Modal.getInstance(el),
                items = this.items.filter(x => x.uuid === this.selectedItems[0]).map(x => x.path);

            $.ajax({
                method: "POST",
                url: `/${window.OCMS.admin_prefix}/media/api/delete`,
                data: {items},
                headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').prop('content')},
                success: (data) => {
                    if(data && data.success) {
                        modal.hide();
                    } else {
                        alert(data.message);
                    }
                },
                complete: () => this.fetchData()
            });
        });

        $('#mediaFileInput').on('change', (e) => {
            const files = e.target.files;
            if(files.length <= 0) {
                return;
            }

            const formData = new FormData;
            formData.append('path', this.queryParams.path);
            let fileIdx = 0;

            for (const file of files) {
                formData.append(`files[${fileIdx}]`, file);
                fileIdx += 1;
            };

            $.ajax({
                method: 'POST',
                url: `/${window.OCMS.admin_prefix}/media/api/upload`,
                data: formData,
                headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').prop('content')},
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $('#mediaLoading').show();
                    $('#mediaLoadingText').text('Uploading...');
                },
                success: (data) => {
                    if(data && !data.success) {
                        alert(data.message);
                    }
                },
                complete: () => this.fetchData()
            });

            $('#mediaFileInput').val(null);
        });
    }

    getBreadCrumbs() {
        if(this.queryParams.path != '/') {
            let html  = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
            const paths = this.queryParams.path.split('/');

            paths.forEach((path, index) => {
                if(index == paths.length - 1) {
                    html += `<li class="breadcrumb-item active" aria-current="page">${path}</li>`;
                } else {
                    html += `<li class="breadcrumb-item">${path}</li>`;
                }
            });

            html += '</ol></nav>';
            return html;
        }

        return this.queryParams.path;
    }

    showFileDetail() {
        if(this.selectedItems.length > 1) {
            $('#multipleSelected').show();
            $('#emptySelected').hide();
            $('#fileSelected').hide();
        } else if(this.selectedItems.length === 1) {
            $('#multipleSelected').hide();
            $('#emptySelected').hide();
            $('#fileSelected').show();

            const detail = this.items.find(x => x.uuid === this.selectedItems[0]);
            if(detail) {
                $('#fileSelected > p[data-label="title"]').html(detail.name);
                $('#fileSelected > p[data-label="last_modified_at"]').html(detail.last_modified_at);

                if(detail.is_dir) {
                    $('#fileSelected > p[data-label="url"]').hide();
                    $('#fileSelected > div[data-label="thumbnail"]').hide();
                    $('#fileSelected > p[data-label="url"] > a').attr('href', '#');
                } else {
                    $('#fileSelected > p[data-label="url"]').show();
                    $('#fileSelected > p[data-label="url"] > a').attr('href', detail.url);

                    if(detail.mime_type.includes('image/')) {
                        $('#fileSelected > div[data-label="thumbnail"]').show();
                        $('#fileSelected > div[data-label="thumbnail"] > img').attr('src', detail.url);
                    } else {
                        $('#fileSelected > div[data-label="thumbnail"]').hide();
                    }
                }
            }
        } else {
            $('#multipleSelected').hide();
            $('#emptySelected').show();
            $('#fileSelected').hide();
        }
    }

    selectItem(e) {
        const uuid = e.getAttribute('data-item-uuid');
        const idx = this.selectedItems.findIndex(x => x === uuid);

        if(e.detail === 1 && e.shiftKey) {
            if(idx !== -1) {
                this.selectedItems = this.selectedItems.filter(x => x != uuid);
            } else {
                this.selectedItems.push(uuid);
            }

            this.items = this.items.filter(x => this.selectedItems.includes(x.uuid));
            e.classList.toggle('selected');
        } else {
            this.selectedItems = [uuid];
            $('#table-media-list .media-item').removeClass('selected');

            if(!e.classList.contains('selected'))
                e.classList.add('selected');
        }

        if(this.selectedItems.length > 0) {
            const item = this.items.find(x => x.uuid == this.selectedItems[0]);
            $('#mediaMoveButton').prop('disabled', false);
            $('#mediaDeleteButton').prop('disabled', false);
            $('#mediaMoveForm input[name="from"]').val(item.path);

            if(!item.is_dir && this.elements.triggeredElement) {
                var triggerElement = this.elements.triggeredElement;
                var selected_valid = true;

                if(triggerElement.getAttribute('data-accept') && triggerElement.getAttribute('data-accept').length > 3) {
                    const valid_types = this.elements.triggeredElement.getAttribute('data-accept').split(',');
                    if(!valid_types.includes(item.mime_type)) {
                        selected_valid = false;
                    }
                }

                $('button[data-trigger="selectMedia"]').prop('disabled', !selected_valid);
            } else {
                $('button[data-trigger="selectMedia"]').prop('disabled', true);
            }
        } else {
            $('#mediaMoveButton').prop('disabled', true);
            $('#mediaDeleteButton').prop('disabled', true);
            $('#mediaMoveForm > input[name="from"]').val();
            $('button[data-trigger="selectMedia"]').prop('disabled', true);
        }

        this.showFileDetail();
    }

    goToPath(e) {
        if(e.getAttribute('data-item-is-dir') === 'true') {
            this.queryParams.path = e.getAttribute('data-item-path');
            this.fetchData();
        }
    }

    applyChanges() {
        const tableBody = this.elements.tableMediaList.find('table > tbody');
        tableBody.html('');

        for(const item of this.items) {
            const itemWrapper = document.createElement('tr');
            const titleColumn = document.createElement('td');
            const sizeColumn = document.createElement('td');
            const dateColumn = document.createElement('td');

            itemWrapper.classList.add('media-item');
            itemWrapper.setAttribute('data-item-uuid', item.uuid);
            itemWrapper.setAttribute('data-item-is-dir', item.is_dir);
            itemWrapper.setAttribute('data-item-path', item.path);

            titleColumn.innerHTML = '<div class="item-title">'
            if(item.is_dir) titleColumn.innerHTML += '<i class="fas fa-folder"></i>';
            else titleColumn.innerHTML += '<i class="fas fa-file"></i>';
            titleColumn.innerHTML += ` ${item.name}</div>`

            if(!item.is_dir) sizeColumn.innerHTML = `<div class="small">${item.size}</div>`
            dateColumn.innerHTML = `<div class="small">${item.last_modified_at}</div>`

            itemWrapper.appendChild(titleColumn);
            itemWrapper.appendChild(sizeColumn);
            itemWrapper.appendChild(dateColumn);

            itemWrapper.addEventListener('click', () => this.selectItem(itemWrapper));
            itemWrapper.addEventListener('dblclick', () => this.goToPath(itemWrapper));

            tableBody.append($(itemWrapper));
        }

        if(this.queryParams.path !== '/') {
            tableBody.prepend(`<tr class="media-item"><td colspan="3">${this.getBreadCrumbs()}</td><tr>`);
            tableBody.prepend(this.elements.backBtn);
        }
    }

    async fetchData() {
        try {
            $('#mediaLoading').show();
            $('#mediaLoadingText').text('Loading...');
            $('button[data-trigger="refreshData"]').prop('disabled', true);

            this.selectedItems = [];
            $('#table-media-list .media-item').removeClass('selected');

            const {data} = await axios.post(this.url + '/list', this.queryParams);

            if(data.success) {
                this.items = data.data;
                this.applyChanges();
            }
        } catch (err) {
            console.error(err)
        } finally {
            $('#mediaLoading').hide();
            $('button[data-trigger="refreshData"]').prop('disabled', false);
        }
    }

    isSelected() {
        if(!this.multiple && !this.selected) {
            return false;
        }

        return true;
    }
}

$(function() {
    var media = new Media(window.OCMS.locale);
});
