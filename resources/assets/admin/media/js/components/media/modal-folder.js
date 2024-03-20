import {ref, reactive} from 'vue';
import {popModal} from 'jenesius-vue-modal';

export default {
    props: ['url', 'path', 'folder'],

    setup(props) {
        const isSubmitting = ref(false);
        const notify = reactive({
            show: false,
            success: true,
            message: ''
        });        
        const formData = reactive({
            folder_name: '',
            path: '/'
        });

        const evt = {
            onSubmit: async (e) => {
                try {
                    isSubmitting.value = true;
                    formData.path = props.path;
                    const { data } = await axios.post(props.url + '/create-folder', formData);

                    if(data.success) {
                        notify.show = true;
                        notify.success = true;
                        notify.message = "Create new folder successfully";
                    } else {
                        notify.show = true;
                        notify.success = false;
                        notify.message = data.message;                        
                    }
                } catch (err) {
                    console.error(err);
                    notify.show = true;
                    notify.success = false;
                    notify.message = "Create new folder failed";                        
                } finally {
                    isSubmitting.value = false;
                    popModal();
                }
            },

            closeModal: () => {
                popModal();
            }
        };

        return {
            isSubmitting,
            formData,
            notify,
            evt
        };
    },

    /*html */
    template: `
    <div class="card card-modal">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Add Folder</h5>
            <button type="button" class="btn btn-transparent shadow-none" @click="evt.closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <form @submit.prevent="evt.onSubmit">
                <div class="form-group mb-3">
                    <label for="folder_name">Folder Name <i class="text-danger">*</i></label>
                    <input class="form-control" placeholder="Enter folder name" v-model="formData.folder_name" :disabled="isSubmitting" required>
                </div>
                <div class="d-flex">
                    <div class="me-2">
                        <button type="submit" class="btn btn-success" :disabled="isSubmitting">
                            Apply
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-dark" @click="evt.closeModal" :disabled="isSubmitting">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    `
}