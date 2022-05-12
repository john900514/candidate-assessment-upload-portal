<template>
    <div class="w-96 bg-primary-100 py-4 md:w-[100%]">
        <div class="w-full text-center">
            <h1 class="text-3xl text-black">{{ taskTitle }}</h1>
            <div class="mx-4 py-4">
                <p class="text-primary"><i>{{ taskDesc }}</i></p>
            </div>
            <div v-if="!started">
                <button class="btn btn-success" @click="started=true">Start this Task</button>
            </div>
            <div class="w-3/4 flex flex-col mx-auto" v-if="started">
                <label class="text-black"> What is your solution? </label>
                <textarea v-model="explanation" class="form-control" :disabled="completed"></textarea>
                <button class="btn btn-warning mt-4" @click="completed=true" v-if="!completed">Mark Task Completed</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TaskViewer",
    props: ['task', 'userStatus'],
    watch: {
        task(task) {
            console.log('new task', this.task)
            this.setUpForm();
        },
        started(flag) {
            if(flag) {
                this.emitStartedStatus('Started');
            }
            else {
                this.emitStartedStatus('stopped');
            }
        },
        completed(flag) {
            if(flag) {
                if(this.explanation !== '') {
                    new Noty({
                        theme: 'sunset',
                        type: 'success',
                        text: 'Task logged.'
                    }).show()
                    this.emitStartedStatus('finished');
                }
                else {
                    new Noty({
                        theme: 'sunset',
                        type: 'warning',
                        text: 'Enter an explanation before submitting.'
                    }).show()
                    this.completed = false;
                }
            }
            else {
                this.emitStartedStatus('started');
            }
        }
    },
    data() {
        return {
            started: false,
            completed: false,
            explanation: ''
        }
    },
    computed: {
        taskTitle() {
            return ('task_name' in this.task)
                ? this.task['task_name']
                : 'No Task Selected'
        },
        taskDesc() {
            return ('task_name' in this.task)
                ? this.task['task_description']
                : 'No Task Selected'
        },

    },
    methods: {
        emitStartedStatus(status) {
            this.$emit('status-change', status)
        },
        setUpForm() {
            switch(this.userStatus.status) {
                case 'Incomplete':
                    this.started = false;
                    this.completed = false;
                    this.explanation = ''
                    break;

                case 'Started':
                    this.started = true;
                    this.completed = false;
                    this.explanation = ''
                    break;
            }
        }
    },
    mounted() {
        console.log('task', this.task)
        this.setUpForm();
    }
}
</script>

<style scoped>

</style>
