export default {
    emits: ['click', 'deleteFolder'],
    props: ['items', 'level', 'activeId'],
    setup (props, ctx) {
        const onClick = (e, partial, item_idx, level) => {
            ctx.emit("click", {event: e, partial, item_idx, level});
        };

        const onChildClick = (data) => {
            ctx.emit("click", data);
        };

        const onDeleteFolder = (event, data) => {
            ctx.emit('deleteFolder', event, data);
        };

        return {
            props,
            onClick,
            onChildClick,
            onDeleteFolder
        }
    },
    template: `
        <ul class="partial-list">
            <li class="partial-list--item" v-for="(partial, idx) in props.items" :key="idx">
                <div 
                    class="partial-item-content"
                    :class="{'is-directory': partial.is_dir, open: partial.is_open, active: partial.id === activeId}" 
                    @click="onClick($event, partial, idx, props.level || 0)"
                >
                    {{partial.name}}
                    <span class="text-danger small ms-2" @click="onDeleteFolder($event, partial)" v-if="partial.is_dir">
                        <i class="fas fa-trash"></i>
                    </span>                      
                </div>
                <template v-if="partial.is_dir && partial.childrens && partial.childrens.length > 0">
                    <Tree :items="partial.childrens" :level="(props.level || 0) + 1" :activeId="activeId" @click="onChildClick" @deleteFolder="onDeleteFolder" v-if="partial.is_open"/>
                </template>
            </li>
        </ul>
    `
}