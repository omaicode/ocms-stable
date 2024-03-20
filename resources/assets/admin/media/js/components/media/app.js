import {pushModal} from "jenesius-vue-modal";
import AddFolderModal from './modal-folder';
import MoveModal from './modal-move';
import DeleteModal from './modal-delete';

export default {
    props: {
        url: {
            type: String,
            required: true
        },

        name: {
            type: String,
            default: 'media'
        },

        selectable: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            isFetching: false,
            items: [],
            files: [],
            selectedItems: [],
            view_mode: 'grid',
            multiple: false,
            selected: false,
            uploading: false,
            searchTimer: null,
            queryParams: {
                path: '/',
                display: 'all',
                orderBy: 'title',
                sortBy: 'asc',
                search: ''
            },
            notify: {
                show: false,
                success: true,
                message: ''
            },
        }
    },

    computed: {
        isSelected() {
            if(!this.multiple && !this.selected) {
                return false;
            }

            return true;
        }
    },

    watch: {
        async 'queryParams.path'() {
            await this.fetchData();
        },
        async 'queryParams.display'() {
            await this.fetchData();
        },
        async 'queryParams.orderBy'() {
            await this.fetchData();
        },
        async 'queryParams.sortBy'() {
            await this.fetchData();
        },
        async 'queryParams.search'() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(async () => {
                await this.fetchData()
            }, 500)
        },
    },

    mounted() {
        this.fetchData()
    },

    methods: {
        async fetchData() {
            try {
                this.isFetching = true;
                const {data} = await axios.post(this.url + '/list', this.queryParams);

                if(data.success) {
                    this.items = data.data;
                }
            } catch (err) {
                console.error(err)
            } finally {
                this.isFetching = false;
            }
        },

        onPathChange(item) {
            if(typeof item == 'string') {
                this.queryParams.path = item || '/';
            } else {
                this.queryParams.path = item.path;
            }
        },

        onMultiple(bool) {
            this.multiple = bool;
        },

        onSelected(bool) {
            this.selected = bool;
        },

        onSelectedItems(items) {
            this.selectedItems = items;
        },

        async showAddFolderModal() {
            const modal = await pushModal(AddFolderModal, {path: this.queryParams.path, url: this.url});
            modal.onclose = async (event) => {
                if(modal.instance?.notify?.show) {
                    this.showNotify(modal.instance?.notify?.message, modal.instance?.notify?.success);
                } 

                await this.fetchData()
            };
        },

        async showMoveModal() {
            const modal = await pushModal(MoveModal, {url: this.url, source: this.selectedItems.map(x => x.path)});
            modal.onclose = async (event) => {
                if(modal.instance?.notify?.show) {
                    this.showNotify(modal.instance?.notify?.message, modal.instance?.notify?.success);
                } 

                await this.fetchData()
            };            
        },

        async showDeleteModal() {
            const modal = await pushModal(DeleteModal, {url: this.url, items: this.selectedItems.map(x => x.path)});
            modal.onclose = async (event) => {
                if(modal.instance?.notify?.show) {
                    this.showNotify(modal.instance?.notify?.message, modal.instance?.notify?.success);
                } 

                await this.fetchData()
            };            
        },

        openFileDialog() {
            this.$refs.fileRef.click();
        },
        
        showNotify(message, success = true, interval = 3500) {
            this.notify = {
                show: true,
                success,
                message
            };

            setTimeout(() => {
                this.notify = {
                    show: false,
                    success: true,
                    message: ''
                };
            }, interval)
        },

        async uploadFiles(e) {
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

            try {
                this.uploading = true;
                const { data } = await axios.post(this.url + '/upload', formData);

                if(data.success) {
                    this.showNotify("Uploaded file(s) successfully");
                    await this.fetchData();
                } else {
                    this.showNotify("Uploaded file(s) failed", false);                    
                }
            } catch (err) {
                console.err(err);
            } finally {
                this.uploading = false;
                this.$refs.fileRef.value = null;
            }
        }
    },

    /*html*/
    template: `
    <div class="media-wrapper">
        <div class="media-wrapper--header">
            <div class="media-wrapper--header_left">
                <div>
                    <button type="button" class="btn btn-sm btn-success text-white me-2" @click="openFileDialog">
                        <i class="fas fa-upload"></i>
                        Upload
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success text-white me-2" @click="showAddFolderModal">
                        <i class="fas fa-folder"></i>
                        Add Folder
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary me-2" :disabled="isFetching" @click="fetchData">
                        <i class="fas fa-refresh" :class="{'fa-spin': isFetching}"></i>
                    </button>
                </div>
                <div>
                    <div class="input-group w-auto me-2">
                        <button type="button" class="btn btn-sm btn-secondary" :disabled="!isSelected" @click="showMoveModal">
                            <i class="fas fa-arrows-left-right"></i>
                            Move
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" :disabled="!isSelected" @click="showDeleteModal">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>  
                <div style="display: none">
                    <div class="input-group w-auto">
                        <button type="button" class="btn btn-sm btn-secondary" :disabled="view_mode == 'grid'" @click="view_mode = 'grid'">
                            <i class="fas fa-list"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" :disabled="view_mode == 'list'" @click="view_mode = 'list'">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" :disabled="view_mode == 'tiles'" @click="view_mode = 'tiles'">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div  class="media-wrapper--header_right">
                <div class="input-group w-auto">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control form-control-sm" placeholder="Search..." v-model="queryParams.search">
                </div>
            </div>
        </div>
        <transition name="fade">
        <div class="media-wrapper--notify" v-if="notify.show && notify.message">
            <div>
                <i class="fas fa-exclamation-circle text-danger" v-if="!notify.success"></i>
                <i class="fas fa-check-circle text-success" v-else></i>
                {{notify.message}}
            </div>
        </div>
        </transition>
        <transition name="fade">
        <div class="media-wrapper--uploading" v-if="uploading">
            <div class="d-flex align-items-center">
                <div class="spinner-border text-warning spinner-border-sm"
                    role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="ms-2">Uploading...</div>
            </div>
        </div>
        </transition>
        <div class="media-wrapper--content">
            <TabDisplay 
                v-model:display="queryParams.display" 
                v-model:orderBy="queryParams.orderBy"
                v-model:sortBy="queryParams.sortBy"
            />
            <TabGrid 
                :items="items" 
                :viewMode="view_mode"
                :path="queryParams.path"
                :selectable="selectable"
                :name="name"
                @goto="onPathChange"
                @multiple="onMultiple"
                @selected="onSelected"
                @selectedItems="onSelectedItems"
                @onPreview="$emit('onPreview', $event)"
            />
        </div>
        <input ref="fileRef" type="file" style="display: none" @change="uploadFiles" multiple>
    </div>
    `
}