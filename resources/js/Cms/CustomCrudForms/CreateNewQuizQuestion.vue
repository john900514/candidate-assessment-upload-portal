<template>
    <form method="POST" :action="'/portal/assets/quizzes/'+questionId+'/questions'">
        <div class="row">
            <div class="form-group col-sm-12">
                <label>Question</label>
                <input type="text" class="form-control" v-model="form.question_name" placeholder="i.e. - Can you do a handstand?">
            </div>

            <div class="form-group col-sm-6">
                <label>Question Type</label>
                <select class="form-control" v-model="questionType">
                    <option :value="null">Select a Question Type</option>
                    <option value="OPEN_ENDED">Open Ended</option>
                    <option value="MULTIPLE_CHOICE">Multiple Choice</option>
                    <option value="TRUE_FALSE">True or False</option>
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label>Expected Answer</label>
                <label class="ml-4" v-if="questionType === 'OPEN_ENDED'">Open Ended Questions do not have pre-set answers.</label>
                <select v-if="questionType === 'TRUE_FALSE'" class="form-control" v-model="form.answer">
                    <option :value="null">Select a Boolean</option>
                    <option :value="true">True</option>
                    <option :value="false">False</option>
                </select>
                <select v-if="questionType === 'MULTIPLE_CHOICE'" class="form-control" v-model="form.answer">
                    <option :value="null">Select an Answer</option>
                    <option value="A">A - {{ availableChoices.A }}</option>
                    <option value="B">B - {{ availableChoices.B }}</option>
                    <option value="C">C - {{ availableChoices.C }}</option>
                    <option value="D">D - {{ availableChoices.D }}</option>
                </select>
            </div>

            <div class="form-group col-sm-12" v-if="questionType === 'MULTIPLE_CHOICE'">
                <label>Multiple Choice Answers</label>
                <input type="text" class="form-control" placeholder="Answer A" v-model="availableChoices.A">
                <input type="text" class="form-control" placeholder="Answer B" v-model="availableChoices.B">
                <input type="text" class="form-control" placeholder="Answer C" v-model="availableChoices.C">
                <input type="text" class="form-control" placeholder="Answer D" v-model="availableChoices.D">
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
    name: "CreateNewQuizQuestion",
    props: ['questionId', 'concentration'],
    watch: {
        questionType(type) {
            this.form.question_type = type;
            this.form.answer = null
        }
    },
    data() {
        return {
            loading: false,
            questionType: null,
            form: {
                'question_name' : '',
                'question_type': null,
                answer: null,
            },
            availableChoices: {
                A: '',
                B: '',
                C: '',
                D: '',
            }
        }
    },
    methods: {
        cancelCreate() {
            this.form = {
                'question_name' : '',
                'question_type': null,
                answer: null,
            };
            this.availableChoices = {};
            this.questionType = null;
            this.$emit('cancel');
        },
        submitCreate() {
            for(let x in this.form) {
                switch(x)
                {
                    case 'question_name':
                        if(this.form[x] === '') {
                            new Noty({
                                theme: 'sunset',
                                type: 'warning',
                                text: 'What is the question you want to ask the candidate?'
                            }).show()
                            return;
                        }
                        break;

                    case 'question_type':
                        if(this.form[x] === null) {
                            new Noty({
                                theme: 'sunset',
                                type: 'warning',
                                text: 'Select a Question Type'
                            }).show()
                            return;
                        }
                        else {
                            if(this.form[x] === 'MULTIPLE_CHOICE') {
                                for(let y in this.availableChoices) {
                                    if(this.availableChoices[y] === '') {
                                        new Noty({
                                            theme: 'sunset',
                                            type: 'warning',
                                            text: 'Make sure to fill in the choice for answer '+y
                                        }).show()
                                    }
                                }
                            }
                        }

                        break;

                    case 'answer':
                        if(this.form['question_type'] !== 'OPEN_ENDED') {
                            if((this.form[x] === null) || this.form[x] === '') {
                                new Noty({
                                    theme: 'sunset',
                                    type: 'warning',
                                    text: 'You need to add the intended Answer'
                                }).show()

                                return;
                            }
                        }
                }
            }

            let _this = this;
            let payload = {
                'quiz_id' : this.questionId,
                'question_name' : this.form.question_name,
                'question_type': this.form.question_type,
            }

            if(this.form.question_type !== 'OPEN_ENDED') {
                payload.answer = this.form.answer
            }

            if(this.form.question_type !== 'MULTIPLE_CHOICE') {
                payload.choices = this.availableChoices
            }

            axios.post('/portal/assets/quizzes/'+this.questionId+'/questions', payload)
                .then(({ data }) => {
                    new Noty({
                        type: 'success',
                        text: 'Awesome, your new question was saved!'
                    }).show()

                    setTimeout(function () {
                        _this.$emit('reload');
                    }, 750);
                })
                .catch(({ response }) => {
                    new Noty({
                        type: 'error',
                        text: 'An error occurred. Your new question was not saved. Try again.'
                    }).show()

                    _this.loading = false;
                })
        }
    },
}
</script>

<style scoped>

</style>
