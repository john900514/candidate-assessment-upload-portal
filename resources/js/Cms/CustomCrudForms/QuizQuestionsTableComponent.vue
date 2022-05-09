<template>
    <table class="table table-responsive-sm table-striped" id="quiz">
        <thead>
        <tr class="text-center">
            <th>Question</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="questions.length == 0" class="text-center">
            <td> No </td>
            <td> questions. </td>
            <td> <button type="button" class="btn btn-primary" @click="openModal()">Create</button> </td>
        </tr>
        <tr v-for="(question, idx) in questions"  v-if="questions.length > 0" class="text-center">
            <td> {{ question['question_name'] }} </td>
            <td> {{ question['question_type'] }} </td>
            <td><button type="button" class="btn btn-danger"><i class="las la-trash" @click="removeTask(question['questionId'])"></i></button></td>
        </tr>
        </tbody>
    </table>
    <div v-if="questions.length > 0" class="text-center">
        <button type="button" class="btn btn-primary" @click="openModal()">Create New Task</button>
    </div>

    <div>
        <vue-final-modal v-model="showModal" classes="modal-container" content-class="modal-content">
            <new-question @cancel="() => closeModal()" :question-id="questionId" @reload="reloadTable()"></new-question>
        </vue-final-modal>
    </div>
</template>

<script>
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'
import NewQuestion from "@/Cms/CustomCrudForms/CreateNewQuizQuestion";


export default {
    name: "QuizQuestionsTableComponent",
    components: {
        NewQuestion,
        VueFinalModal,
        ModalsContainer
    },
    props: ['questionId', 'preQuestions'],
    data() {
        return {
            questions: [],
            showModal: false
        }
    },
    computed: {
        questions() {
            let r = [];

            for(let uuid in this.preQuestions) {
                this.preQuestions[uuid].questionId = uuid;
                r.push(this.preQuestions[uuid])
            }

            return r;
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
        removeTask(taskName) {
            let answer = confirm('Confirm that you want to remove this task.');

            if(answer) {
                let payload = {
                    'question_id': this.questionId,
                    'question_name' : taskName,
                }

                /*
                axios.delete('/portal/assets/', {data: payload})
                    .then(({ data }) => {
                        new Noty({
                            type: 'success',
                            text: 'Alright the task was deactivated.'
                        }).show()

                        setTimeout(function () {
                            window.location.reload()
                        }, 750);
                    })
                    .catch(({ response }) => {
                        new Noty({
                            type: 'error',
                            text: 'An error occurred. Your new task was not deactivated. Try again.'
                        }).show()

                        _this.loading = false;
                    })

                 */
            }
        }
    },
    mounted() {
        for(let x in this.preTasks) {
            this.tasks.push(this.preTasks[x]);
        }
    }
}
</script>

<style scoped>
#quiz {
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
