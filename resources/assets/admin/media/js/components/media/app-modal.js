import { ref } from "vue";

export default {
    props: ['url', 'name'],
    setup(props) {
        const media = ref(null);
        const applyPreview = (item) => {
            media.value = item;
        };

        return {
            media,
            applyPreview
        };
    },

    /*html*/
    template: `
    <div class="media-modal-wrapper shadow-sm">
        <App 
            :url="url" 
            :name="name"
            @onPreview="applyPreview"
            selectable/>
    </div>
    `
}