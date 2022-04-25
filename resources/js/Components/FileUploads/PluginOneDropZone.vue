<template>
    <!-- add `data-active` and the event listeners -->
    <div :data-active="active" @dragenter.prevent="setActive" @dragover.prevent="setActive" @dragleave.prevent="setInactive" @drop.prevent="onDrop">
        <!-- share state with the scoped slot -->
        <slot :dropZoneActive="active"></slot>
    </div>
</template>

<script>
export default {
    name: "PluginOneDropZone",
    data() {
        return {
            active: false,
            inActiveTimeout: null,
            events: ['dragenter', 'dragover', 'dragleave', 'drop'],
        }
    },
    methods: {
        preventDefaults(e) {
            e.preventDefault()
        },
        onDrop(e) {
            this.setInactive();
            this.$emit('files-dropped', [...e.dataTransfer.files])
        },
        setActive() {
            this.active = true;
            this.inActiveTimeout = null;
        },
        setInactive() {
            let _this = this;

            // wrap it in a `setTimeout`
            this.inActiveTimeout = setTimeout(() => {
                _this.active = false;
            }, 50)

        }
    },
    mounted() {
        this.events.forEach((eventName) => {
            document.body.addEventListener(eventName, this.preventDefaults)
        })
    },
    unmounted() {
        this.events.forEach((eventName) => {
            document.body.removeEventListener(eventName, this.preventDefaults)
        })
    }
}
</script>

<style scoped>

</style>
