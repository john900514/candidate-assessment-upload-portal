<template>
    <table class="table table-responsive-sm table-striped" id="assessment">
        <thead>
        <tr class="text-center">
            <th>Quiz</th>
            <th>Concentration</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="quizzes.length == 0" class="text-center">
            <td> No quizzes </td>
            <td> assigned.</td>
            <td> <button type="button" class="btn btn-primary" @click="openModal()">Assign</button> </td>
        </tr>
        <tr v-for="(quiz, idx) in quizzes"  v-if="quizzes.length > 0" class="text-center">
            <td> {{ quiz['name'] }} </td>
            <td> {{ quiz['concentration'] }} </td>
            <td><button type="button" class="btn btn-danger"><i class="las la-trash" @click="removeQuiz(quiz['quiz_id'])"></i></button></td>
        </tr>
        </tbody>
    </table>
    <div v-if="quizzes.length > 0" class="text-center">
        <button type="button" class="btn btn-primary" @click="openModal()">Link Another Quiz</button>
    </div>


    <div>
        <vue-final-modal v-model="showModal" classes="modal-container" content-class="modal-content">
            <add-quiz @cancel="() => closeModal()" :assessment-id="assessmentId" @reload="reloadTable()" :options="availableQuizzes"></add-quiz>
        </vue-final-modal>
    </div>

</template>

<script>
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'
import AddQuiz from "@/Cms/CustomCrudForms/Screens/AddQuizToAssessment";

export default {
    name: "AssessmentQuizzesTableComponent",
    components: {
        AddQuiz,
        VueFinalModal,
        ModalsContainer
    },
    props: ['assessmentId', 'preQuizzes'],
    data() {
        return {
            quizzes: [],
            availableQuizzes: [],
            showModal: false
        }
    },
    methods: {
        openModal() {
            this.showModal = true
        },
        closeModal() {
            this.showModal = false
        },
        reloadTable() {
            window.location.reload();
        },
        removeQuiz(quizId) {
            let answer = confirm('Confirm that you want to remove this task.');

            if(answer) {
                let _this = this;
                this.loading = true;
                let payload = {
                    'assessment_id': this.assessmentId,
                    'quiz_id' : quizId,
                }

                axios.delete('/portal/assessments/quizzes', {data: payload})
                    .then(({ data }) => {
                        new Noty({
                            type: 'success',
                            text: 'Alright the quiz was deactivated.'
                        }).show()

                        setTimeout(function () {
                            window.location.reload()
                        }, 750);
                    })
                    .catch(({ response }) => {
                        new Noty({
                            type: 'error',
                            text: 'An error occurred. The quiz deactivated. Try again.'
                        }).show()

                        _this.loading = false;
                    })
            }
        },
        getAvailableQuizzes() {
            axios.get('/portal/assessments/quizzes?assessment_id='+this.assessmentId)
                .then(({ data }) => {
                    this.availableQuizzes = data['options'];
                    console.log(data);
                })
                .catch(({ response }) => {
                    this.availableQuizzes = [];
                })
        }
    },
    mounted() {
        this.getAvailableQuizzes();

        for(let x in this.preQuizzes) {
            this.quizzes.push(this.preQuizzes[x]);
        }

        console.log('quizzes', this.quizzes);
    }
}
</script>

<style scoped>
#assessment {
    width: 100% !important;
    table-layout: fixed;
    word-break: break-word;
}
</style>

<style scoped>
@media screen and (max-width: 500px) {
    ::v-deep .modal-content {
        margin: 0 10%;
    }
}

@media screen and (min-width: 501px) {
    ::v-deep .modal-content {
        margin: 0 15%;
    }
}
::v-deep .modal-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
::v-deep .modal-content {
    display: flex;
    flex-direction: column;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    background: #fff;
}
.modal__title {
    font-size: 1.5rem;
    font-weight: 700;
}
</style>

<style scoped>
.dark-mode div::v-deep .modal-content {
    border-color: #2d3748;
    background-color: #1a202c;
}
</style>
