export default {
    emits: ['update:display', 'update:orderBy', 'update:sortBy'],

    data() {
        return {
            tabs: [
                {icon: 'fa-th-list', title: 'All', value: "all"},
                {icon: 'fa-image', title: 'Images', value: "images"},
                {icon: 'fa-video', title: 'Video', value: "video"},
                {icon: 'fa-volume-up', title: 'Audio', value: "audio"},
                {icon: 'fa-file', title: 'Documents', value: "documents"},
            ]
        }
    },
    
    computed: {
        displayValue: {
            get() {
                return this.display
            },

            set(val) {
                this.$emit('update:display', val);
            }
        },
        orderByValue: {
            get() {
                return this.orderBy
            },

            set(val) {
                this.$emit('update:orderBy', val);
            }
        },
        sortByValue: {
            get() {
                return this.sortBy
            },

            set(val) {
                this.$emit('update:sortBy', val);
            }
        },
    },

    props: {
        display: {
            type: String,
            default: 'all'
        },

        orderBy: {
            type: String,
            default: 'title'
        },
        
        sortBy: {
            type: String,
            default: 'asc'
        }
    },

    /*html*/
    template: `
    <div class="media-tab-display--title">
        <div class="media-tab-display">
            <div class="text-gray-400 font-bold p-3">Display</div>
            <ul class="display-list">
                <li 
                    class="display-list--item" 
                    v-for="(tab, index) in tabs" 
                    key="index" 
                    :class="{active: display == tab.value}"
                    @click="$emit('update:display', tab.value)"
                >
                    <span class="icon"><i class="fas" :class="tab.icon"></i></span>
                    <span class="title">{{tab.title}}</span>
                </li>
            </ul>
            <div class="form-group px-3 pb-3 pt-0">
                <label class="text-gray-400 fw-normal" for="order_by">Order by</label>
                <select class="form-select form-select-sm" @change="$emit('update:orderBy', $event.target.value)">
                    <option value="title">Title</option>
                    <option value="size">Size</option>
                    <option value="last_modified">Last Modified</option>
                </select>
            </div>
            <div class="form-group px-3 pb-3 pt-0">
                <label class="text-gray-400 fw-normal" for="order_by">Direction</label>
                <select class="form-select form-select-sm" @change="$emit('update:sortBy', $event.target.value)">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>
    </div>
    `
}