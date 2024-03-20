import {ref, reactive} from 'vue';
import {popModal} from 'jenesius-vue-modal';

export default {
    emits: ['onNotify'],
    props: ['url', 'source'],

    setup(props, {emit}) {
        const isSubmitting = ref(false);
        const notify = reactive({
            show: false,
            success: true,
            message: ''
        });
        const formData = reactive({
            from: [],
            to: '/'
        });

        const evt = {
            onSubmit: async (e) => {
                try {
                    isSubmitting.value = true;
                    formData.from = props.source;
                    const { data } = await axios.post(props.url + '/move', formData);

                    if(data.success) {
                        notify.show = true;
                        notify.success = true;
                        notify.message = "Move file(s) or folder(s) successfully";
                    } else {
                        notify.show = true;
                        notify.success = false;
                        notify.message = "Move file(s) or folder(s) failed";                        
                    }
                } catch (err) {
                    console.error(err);
                    notify.show = true;
                    notify.success = false;
                    notify.message = "Move file(s) or folder(s) failed";                      
                } finally {
                    popModal();
                    isSubmitting.value = false;
                }
            },

            closeModal: () => {
                popModal();
            }
        };

        return {
            notify,
            isSubmitting,
            formData,
            evt
        };
    },

    /*html */
    template: `
    <div class="card card-modal">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Move files or folders</h5>
            <button type="button" class="btn btn-transparent shadow-none" @click="evt.closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <form @submit.prevent="evt.onSubmit">
                <div class="form-group mb-3">
                    <label for="folder_name">Destination folder <i class="text-danger">*</i></label>
                    <input class="form-control" placeholder="Enter destination folder" v-model="formData.to" :disabled="isSubmitting" required>
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