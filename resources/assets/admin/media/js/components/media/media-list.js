import {ref, computed} from 'vue';
import _ from 'lodash';

export default {
    emits: ['update:items', 'selected', 'multiple', 'goto'],

    props: {
        path: {
            type: String,
            default: '/'
        },
        
        mode: {
            type: String,
            default: 'grid'
        },

        items: {
            type: Array,
            default: () => []
        }
    },

    setup(props, {emit}) {
        const selectedList = ref([]);
        const pathHtml = computed(() => {
            if(props.path != '/') {
                let html  = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
                const paths = props.path.split('/');

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

            return props.path;
        });

        const methods = {
            clearSelectedItems: () => {
                selectedList.value = []
            },

            selectItem: (item) => {
                const idx = selectedList.value.findIndex(x => x === item.uuid);
                if(idx !== -1) {
                    selectedList.value = selectedList.value.filter(x => x != item.uuid);
                } else {
                    selectedList.value.push(item.uuid);
                }

                const list = props.items.filter(x => selectedList.value.includes(x.uuid));
                emit('update:items', list);
            },

            isItemSelected(uuid) {
                return selectedList.value.findIndex(x => x == uuid) !== -1;
            }
        };        
        
        const events = {
            click: (e, item) => {
                if(e.detail === 1 && e.shiftKey) {
                    methods.selectItem(item);
                    emit('selected', null);
                    emit('multiple', true);
                } else if(e.detail === 1 && !e.shiftKey) {
                    methods.clearSelectedItems();
                    methods.selectItem(item);
                    emit('selected', item);
                    emit('multiple', false);
                } else {
                    if(item.is_dir) {
                        methods.clearSelectedItems();
                        emit('selected', null);
                        emit('multiple', false);                        
                        emit('goto', item);
                    }
                }
            },

            back: () => {
                const paths = props.path.split('/');
                paths.splice(paths.length - 1, 1);
                emit('goto', paths.join('/'));  
            }
        };

        return {
            selectedList,
            pathHtml,
            methods,
            events
        };
    },

    /*html */
    template: `
    <div class="media-item-wrapper" :class="{'mode-grid': mode == 'grid'}">
        <div class="media-item-content" v-if="mode == 'grid'">
            <table class="table">
                <colgroup>
                    <col>
                    <col width="130px">
                    <col width="130px">
                </colgroup>
                <tbody>
                    <tr class="media-item" v-if="path != '/'">
                        <td v-html="pathHtml"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="media-item" v-if="path != '/'" @click="events.back">
                        <td>
                            <div class="item-title">
                                <i class="fas fa-arrow-left"></i>
                                Back ..
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="media-item" v-for="(item, idx) in items" :key="idx" @click="events.click($event, item)" :class="{selected: methods.isItemSelected(item.uuid)}">
                        <td>
                            <div class="item-title">
                                <i class="fas fa-folder" v-if="item.is_dir"></i>
                                <i class="fas fa-file" v-else></i>
                                {{item.name}}
                            </div>
                        </td>
                        <td>
                            <div class="small" v-if="!item.is_dir">{{ item.size }}</div>
                        </td>
                        <td>
                            <div class="small">{{item.last_modified_at}}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    `
}