import {ref, computed, watch, onMounted} from 'vue';
import {openModal} from "jenesius-vue-modal";
import AppModal from './app-modal';

export default {
    props: ['name', 'placeholder', 'label', 'url', 'savePath', 'value', 'preview'],

    setup(props) {
        const previewImage = ref(null);
        const media = ref(null);
        const previewComputed = computed({
            get() {
                if(!previewImage.value && props.preview) {
                    return `url(${props.preview})`;
                }
                
                return previewImage.value ? `url(${previewImage.value})` : null;
            },

            set(val) {
                previewImage.value = val;
            }
        });

        watch(previewImage, (newval) => {
            previewComputed.value = newval;
        }, {immediate: true});

        onMounted(() => {
            if(props.value) {
                media.value = props.value;
            }

            if(props.preview) {
                previewImage.value = props.preview;
            }
        });

        const showModal = async () => {
            const modal = await openModal(AppModal, {url: props.url});
            modal.onclose = (event) => {
                if(modal.instance.media) {
                    const instance_media = modal.instance.media;
                    previewComputed.value = instance_media.url;

                    if(instance_media.stored_db) {
                        media.value = instance_media.uuid;
                    } else {
                        media.value = instance_media.path;
                    }
                }
            };            
        };

        return {
            previewComputed,
            media,
            showModal
        }
    },

    /*html*/
    template: `
    <div class="media-form-app" @click="showModal">
        <div class="media-form-button-wrapper">
            <div class="media-form-button-wrapper--content" :style="{backgroundImage: previewComputed}">
                <div class="form-button-placeholder" v-if="!previewComputed">{{placeholder}}</div>
            </div>
        </div>
        <input type="hidden" :name="name" v-model="media">
    </div>
    `
}