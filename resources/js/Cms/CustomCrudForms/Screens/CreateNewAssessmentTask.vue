<template>
    <form method="POST" action="/portal/assessments/tasks">
        <div class="row">
            <div class="form-group col-sm-12">
                <label>Task Name</label>
                <input type="text" class="form-control" v-model="form.task_name" placeholder="i.e. - Fix UI Bug">
            </div>

            <div class="form-group col-sm-12">
                <label>Task Description</label>
                <textarea type="text" class="form-control" v-model="form.task_description" />
            </div>

            <div class="form-group col-sm-12">
                <input type="checkbox"  v-model="form.optional">
                <label class="ml-4">Is an optional task</label>
            </div>

            <div class="form-group col-sm-12">
                <button type="button" class="btn btn-success" @click="submitCreate()" :disabled="loading"> Create </button>
                <button type="button" class="btn btn-danger ml-4" @click="cancelCreate()" :disabled="loading"> Cancel </button>
            </div>
        </div>
    </form>

</template>

<script>
export default {
    name: "CreateNewAssessmentTask",
    props: ['assessmentId'],
    data() {
        return {
            loading: false,
            form: {
                'task_name' : '',
                'task_description': '',
                optional: true
            }
        }
    },
    methods: {
        cancelCreate() {
            this.form = {
                'task_name' : '',
                'task_description': '',
                optional: true
            };
            this.$emit('cancel');
        },
        submitCreate() {
            for(let x in this.form) {
                if(this.form[x] === '') {
                    new Noty({
                        theme: 'sunset',
                        type: 'warning',
                        text: x+' needs to be filled out first!'
                    }).show()

                    return;
                }
            }

            let _this = this;
            let payload = {
                'assessment_id': this.assessmentId,
                'task_name' : this.form['task_name'],
                'task_description': this.form['task_description'],
                required: !this.form['optional']
            }
            axios.post('/portal/assessments/tasks', payload)
                .then(({ data }) => {
                    new Noty({
                        type: 'success',
                        text: 'Awesome, your new task was saved!'
                    }).show()

                    setTimeout(function () {
                        _this.$emit('reload');
                    }, 750);
                })
                .catch(({ response }) => {
                    new Noty({
                        type: 'error',
                        text: 'An error occurred. Your new task was not saved. Try again.'
                    }).show()

                    _this.loading = false;
                })
        }
    }
}
</script>

<style scoped>

</style>
