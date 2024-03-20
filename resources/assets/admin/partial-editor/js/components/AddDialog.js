export default {
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },

        trans: {
            type: Object,
            default: () => {}
        }
    },

    emits: ['update:modelValue'],

    data() {
        return {
            isDialogShow: false,
            partial_value: ""
        }
    },

    watch: {
        modelValue: {
            immediate: true,
            handler(newval) {
                this.toggleDialog = newval;
            }
        }
    },

    computed: {
        toggleDialog: {
            get() {
                return this.isDialogShow
            },

            set(val) {
                this.isDialogShow = val
                this.$emit('update:modelValue', val)
            }
        }
    },

    mounted() {
        if(this.modelValue) {
            this.toggleDialog = this.modelValue;
        }
    },

    methods: {
        onSubmit() {
            this.$emit('submit', this.partial_value);
            this.partial_value = "";
            this.isDialogShow = false;
        }
    },

    template: `
    <div class="partial-dialog" :class="{show: toggleDialog}">
        <div class="partial-dialog--content">
            <div class="fw-bold mb-3">{{ trans.new_partial }}</div>
            <input class="form-control form-control-sm mb-3" placeholder="example/partial01" v-model="partial_value">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" @click="toggleDialog = false">
                  {{ trans.cancel }}
                </button>
                <button type="button" class="btn btn-success btn-sm" @click="onSubmit">
                  {{ trans.add }}
                </button>
            </div>
        </div>
    </div>
    `
}
