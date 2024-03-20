<template>
    <div class="partial-editor-wrapper">
        <div class="row g-0">
            <div class="col-12 col-xl-3 col-lg-3">
                <div class="card position-relative rounded-0">
                    <AddDialog v-model="addDialogShown" @submit="savePartial" :trans="trans"/>
                    <div class="card-header d-flex justify-content-between px-3 py-2">
                        <div class="fw-bold">
                            <i class="fas fa-cubes me-2 text-success"></i>
                            {{ trans.partials }}
                        </div>
                        <div class="partial-actions">
                            <div class="partial-action text-success" @click="addDialogShown = !addDialogShown">
                                <i class="fas fa-plus"></i>
                                {{ trans.add }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="input-group">
                            <span class="input-group-text rounded-0 shadow-none border-top-0 border-start-0">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control rounded-0 shadow-none border-top-0 border-end-0" :placeholder="trans.search || 'Search'" v-model="searchInput">
                        </div>
                    </div>
                    <div class="card-body p-0 position-relative" style="min-height: 500px; max-height: 500px; overflow-y: auto">
                        <Tree :items="filteredPartials" @click="onPartialClick" @deleteFolder="deleteItem" :activeId="selectedPartial ? selectedPartial.id : 'none'"/>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-9 col-lg-9" v-if="selectedPartial">
                <div class="card card-partial-content">
                    <div class="card-header px-3 py-2 d-flex justify-content-between align-items-center">
                        <div class="fw-bold mb-0" >
                            <i class="fas fa-cube text-success"></i>
                            {{selectedPartial.name}}
                        </div>
                        <div class="d-flex">
                            <div class="partial-action text-dark">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 position-relative">
                        <div class="partial-loading" v-if="partialLoading">
                            <div class="spinner-border text-white spinner-border-sm"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="partital-actions" v-if="!previewing">
                            <div class="partial-action" @click="savePartial(null)">
                                <i class="fas fa-save text-success"></i>
                                <span>{{ trans.save }}</span>
                            </div>
                            <div class="partial-action" @click="previewPartial(true)">
                                <i class="fas fa-eye text-info"></i>
                                <span>{{ trans.preview }}</span>
                            </div>
                            <div class="partial-action text-dark" @click="deleteItem">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                        <div class="partital-actions" v-else>
                            <div class="partial-action" @click="previewPartial(false)">
                                <i class="fas fa-times text-warning"></i>
                                <span>{{ trans.back }}</span>
                            </div>
                        </div>
                        <div class="partial-content" v-if="!previewing">
                            <Codemirror
                                :value="selectedPartialContent"
                                :options="cmOptions"
                                border
                                @change="onPartialContentChanged"
                            />
                        </div>
                        <div class="partial-content" v-else>
                            <iframe class="w-100 h-100" style="text-align:center;" id="previewIframe" :src="getPreviewUrl"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Tree from './Tree';
import AddDialog from './AddDialog';
import {toRefs, ref, onMounted, computed} from 'vue';
import axios from '../axios';
import Codemirror from "codemirror-editor-vue3";

import "codemirror/mode/php/php.js";
import 'codemirror/lib/codemirror.css';
import "codemirror/theme/idea.css";

export default {
    props: ['trans', 'preview_url'],
    components: {Tree, Codemirror, AddDialog},
    setup (props) {
        const { trans, preview_url } = toRefs(props)
        const apiURL          = window.OCMS?.apiURL;
        const partials        = ref([]);
        const selectedPartial = ref(null);
        const selectedPartialContent = ref("");
        const searchInput = ref("");
        const partialLoading  = ref(false);
        const addDialogShown  = ref(false);
        const previewing = ref(false);
        const cmOptions =  ref({
            mode: "application/x-httpd-php", // Language mode
            theme: "idea",
            lineNumbers: true, // Show line number
            smartIndent: true,
            indentUnit: 4,
            indentWithTabs: true,
            tabSize: 4,
            lineWrapping: true,
            matchBrackets: true,
        });

        //Computed
        const getPreviewUrl = computed(() => {
            if(!selectedPartial.value) return '';
            const {path, basename} = selectedPartial.value;
            return preview_url.value + '?partial=' + btoa(path + `/${basename}`);
        });

        //Methods
        const fetchPartials = async () => {
            try {
                partialLoading.value = true;
                const { data } = await axios.get(apiURL + '/tree', {
                    params: {
                        search: searchInput.value
                    }
                });

                if(data.success) {
                    partials.value = data.data
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        };

        const getPartialContent = async (partial) => {
            try {
                partialLoading.value = true;
                const path     = partial.path + `/${partial.basename}`;
                const { data } = await axios.post(apiURL + '/content', {path});

                if(data.success) {
                    selectedPartialContent.value = data.data.content
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        };

        const onPartialClick = async ({_, partial, index, level}) => {
            if(partial.is_dir) {
                partial.is_open = !partial.is_open;
            } else {
                previewing.value = false;
                selectedPartialContent.value = "";
                selectedPartial.value = partial;
                partial.is_open = true;

                await getPartialContent(partial);
            }

            partials.value[level][index] = partial;
        }

        const
        onPartialContentChanged = async (e) => {
            selectedPartialContent.value = e;
        },
        savePartial = async (save_path = null) => {
            try {
                partialLoading.value = true;
                const partial  = selectedPartial.value;
                const path     = save_path ? save_path : partial.path + `/${partial.basename}`;
                const { data } = await axios.put(apiURL + '/content', {path, content: selectedPartialContent.value || ""});

                if(data.success) {
                    Notyf.success(props.trans?.save_success);
                    await fetchPartials();
                } else {
                    Notyf.error(props.trans?.something_went_wrong);
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        },
        deleteItem = (event, data = null) => {
            if(!selectedPartial && !data) return;
            let partial = selectedPartial.value;

            if(data) {
                partial = data;
            }

            Swal.fire({
                icon: 'question',
                title: props.trans.delete_title,
                html: `${props.trans.delete_content} <b>${partial.name}</b>`,
                showCancelButton: true,
                cancelButtonText: props.trans.cancel,
                confirmButtonText: props.trans.delete_button,
                confirmButtonColor: "var(--bs-danger)",
            }).then(async ({isConfirmed}) => {
                if(isConfirmed) {
                    try {
                        partialLoading.value = true;
                        const path     = partial.is_dir ? partial.path : partial.path + `/${partial.basename}`;
                        const { data } = await axios.post(apiURL + '/delete', {path});

                        if(data.success) {
                            Notyf.success(props.trans.delete_success);
                            selectedPartial.value = ""
                            selectedPartialContent.value = ""
                            await fetchPartials()
                        } else {
                            Notyf.error(props.trans.something_went_wrong);
                        }
                    } catch (err) {
                        console.log(err)
                    } finally {
                        partialLoading.value = false;
                    }
                }
            });
        },
        previewPartial = (is_preview) => {
            previewing.value = is_preview;
        },
        filteredPartials = computed(() => {
            return partials.value.filter(x => {
                if(!searchInput.value) return true;
                return x.name.includes(searchInput.value);
            })
        });

        onMounted(async () => {
            await fetchPartials()
        });

        return {
            partialLoading,
            selectedPartial,
            selectedPartialContent,
            fetchPartials,
            onPartialClick,
            cmOptions,
            onPartialContentChanged,
            savePartial,
            deleteItem,
            addDialogShown,
            trans,
            preview_url,
            previewPartial,
            previewing,
            getPreviewUrl,
            searchInput,
            filteredPartials
        }
    }
}
</script>
