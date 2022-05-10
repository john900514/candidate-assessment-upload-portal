<template>
    <form method="POST" action="/portal/assessments/quizzes">
        <div class="row">
            <div class="form-group col-sm-12">
                <label>Available Quizzes</label>
                <select v-model="selectedQuiz" class="form-control">
                    <option :value="''"> {{ (options.length > 0 ) ? 'Select a Quiz' : 'No Quizzes Available' }}</option>
                    <option v-for="(option, idx) in options" :value="option.id">{{ option.name }}</option>
                </select>
            </div>

            <div class="form-group col-sm-12" v-show="options.length > 0 ">
                <button type="button" class="btn btn-success" @click="submitCreate()" :disabled="loading"> Add </button>
                <button type="button" class="btn btn-danger ml-4" @click="cancelCreate()" :disabled="loading"> Cancel </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "AddQuizToAssessment",
    props: ['assessmentId', 'options'],
    data() {
        return {
            loading: false,
            selectedQuiz: ''
        }
    },
    methods: {
        cancelCreate() {
            this.selectedQuiz = '';
            this.$emit('cancel');
        },
        submitCreate() {
            if(this.selectedQuiz === '') {
                new Noty({
                    theme: 'sunset',
                    type: 'warning',
                    text: 'Select a Quiz to Add!'
                }).show()

                return;
            }

            let _this = this;
            this.loading = true;
            let payload = {
                'assessment_id': this.assessmentId,
                'quiz_id': this.selectedQuiz
            };

            axios.post('/portal/assessments/quizzes', payload)
                .then(({ data }) => {
                    new Noty({
                        type: 'success',
                        text: 'Awesome, the quiz was linked to this assessment!'
                    }).show()

                    setTimeout(function () {
                        _this.$emit('reload');
                    }, 750);
                })
                .catch(({ response }) => {
                    new Noty({
                        type: 'error',
                        text: 'An error occurred. Your quiz was not linked. Try again.'
                    }).show()

                    _this.loading = false;
                })
        }
    },
}
</script>

<style scoped>

</style>
