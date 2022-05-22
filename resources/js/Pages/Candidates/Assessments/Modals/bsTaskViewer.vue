<template>
    <div class="w-96 bg-primary-100 py-4 md:w-[100%]">
        <div class="w-full text-center">
            <h1 class="text-3xl text-black">{{ taskTitle }}</h1>
            <div class="mx-4 py-4">
                <p class="text-primary"><i>{{ taskDesc }}</i></p>
            </div>
            <div class="w-3/4 flex flex-col mx-auto">
                <textarea v-model="task.response" class="form-control" disabled></textarea>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "bsTaskViewer",
    props: ['task', 'userStatus'],
    watch: {

    },
    data() {
        return {
        }
    },
    computed: {
        taskTitle() {
            return ('name' in this.task)
                ? this.task['name']
                : 'No Task Selected'
        },
        taskDesc() {
            return ('desc' in this.task)
                ? this.task['desc']
                : 'No Task Selected'
        },

    },
    methods: {

    },
    mounted() {
        console.log('task', this.task)
    }
}
</script>

<style scoped>

</style>
