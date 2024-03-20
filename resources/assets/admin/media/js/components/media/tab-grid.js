import {ref} from 'vue';
import MediaList from './media-list';
import { closeModal } from 'jenesius-vue-modal';

export default {
    components: {MediaList},
    emits: ['goto', 'multiple', 'selected', 'selectedItems', 'onPreview'],

    props: {
        path: {
            type: String,
            default: '/'
        },

        items: {
            type: Array,
            default: () => []
        },

        viewMode: {
            type: String,
            default: 'grid'
        },

        selectable: {
            type: Boolean,
            default: false
        },

        name: {
            type: String,
            default: 'image'
        }
    },

    setup(props, {emit}) {
        const selectedItem = ref(null);
        const isMultiple = ref(false);
        const methods = {
            getItemThumbnail: (item) => {
                if(item.mime_type.indexOf('image/') !== -1) {
                    return item.url;
                }

                return 'data:image/jpeg;base64,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';
            }
        };
        const evt = {
            onItemSelected: (item) => {
                selectedItem.value = item;
                emit('selected', item ? true : false);
            },

            updateSelectedItems: (items) => {
                emit('selectedItems', items);
            },

            onMultiple: (bool) => {
                isMultiple.value = bool;
                emit('multiple', bool);
            },

            applySelectable: () => {
                emit('onPreview', selectedItem.value);
                closeModal();
            }
        };

        return {
            evt,
            methods,
            selectedItem,
            isMultiple
        }
    },

    /*html*/
    template: `
    <div class="media-tab-grid">
        <div class="media-tab-grid--list">
            <MediaList 
                :mode="viewMode"
                :items="items" 
                :path="path"
                @selected="evt.onItemSelected" 
                @multiple="evt.onMultiple"
                @goto="$emit('goto', $event)"
                @update:items="evt.updateSelectedItems"
            />
        </div>
        <div class="media-tab-grid--detail">
            <div class="detail-empty" v-if="!selectedItem && !isMultiple">
                <div class="text-center">
                    <img src="/modules/media/img/select-empty.png" height="48" width="48">
                    <div class="mt-2 small text-gray-400">Nothing is selected</div>
                </div>
            </div>
            <div class="detail-empty" v-if="isMultiple">
                <div class="text-center">
                    <img src="/modules/media/img/select-empty.png" height="48" width="48">
                    <div class="mt-2 small text-gray-400">Multiple items selected</div>
                </div>
            </div>
            <div class="detail-info" v-if="selectedItem && !isMultiple">
                <div class="thumbnail" v-if="!selectedItem.is_dir">
                    <img :src="methods.getItemThumbnail(selectedItem)">
                </div>
                <label>Title</label>
                <p data-label="title">{{selectedItem.name}}</p>
                <label>Last modifed at</label>
                <p data-label="last_modified_at">{{selectedItem.last_modified_at}}</p>
                <label v-if="!selectedItem.is_dir">URL</label>
                <p data-label="url" v-if="!selectedItem.is_dir">
                    <a :href="selectedItem.url" target="_blank" class="text-decoration-underline">Click here</a>
                </p>
                <div class="mt-3" v-if="!selectedItem.is_dir && selectable">
                    <button type="button" class="btn btn-success w-100" @click="evt.applySelectable">
                        SELECT
                    </button>
                </div>
            </div>            
        </div>
    </div>
    `
}