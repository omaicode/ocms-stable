import {ref, reactive} from 'vue';
import {popModal} from 'jenesius-vue-modal';

export default {
    emits: ['onNotify'],
    props: ['url', 'items'],

    setup(props, {emit}) {
        const isSubmitting = ref(false);
        const notify = reactive({
            show: false,
            success: true,
            message: ''
        });

        const evt = {
            onSubmit: async (e) => {
                try {
                    isSubmitting.value = true;
                    const { data } = await axios.post(props.url + '/delete', {items: props.items});

                    if(data.success) {
                        notify.show = true;
                        notify.success = true;
                        notify.message = "Deleted file(s) or folder(s) successfully";
                    } else {
                        notify.show = true;
                        notify.success = false;
                        notify.message = "Deleted file(s) or folder(s) failed";                        
                    }
                } catch (err) {
                    console.error(err);
                    notify.show = true;
                    notify.success = false;
                    notify.message = "Deleted file(s) or folder(s) failed";                      
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
            evt
        };
    },

    /*html */
    template: `
    <div class="card card-modal">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Delete files or folders</h5>
            <button type="button" class="btn btn-transparent shadow-none" @click="evt.closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <form @submit.prevent="evt.onSubmit">
                <div class="alert alert-danger">
                    <div class="fw-bold"><i class="fas fa-exclamation-circle"></i> Are you sure?</div>
                    <span>Are you sure you want to delete these file(s) or folder(s)?</span>
                </div>
                <div class="d-flex">
                    <div class="me-2">
                        <button type="submit" class="btn btn-success" :disabled="isSubmitting">
                            Confirm
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