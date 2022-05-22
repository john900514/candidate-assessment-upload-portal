<template>
    <div class="flex flex-col">
        <div class="row col-12">
            <h3 class="text-center col-12">{{ tableTitle }}</h3>
        </div>
        <div class="row col-12">
            <div class="col-12">
                <div v-if="slide !== 'assessments'"><button type="button" class="badge badge-warning" @click="changeSlides('assessments', '')"> Go Back</button></div>
                <table class="table table-responsive-sm table-sm w-full">
                    <thead>
                        <tr>
                            <th v-for="(col, idx) in tableColumns">{{ col }}</th>
                        </tr>
                    </thead>
                    <tbody v-if="(slide === 'assessments')">
                        <tr v-if="assessments.length === 0">
                            <td>There was an error</td>
                            <td>or Job has</td>
                            <td> no assessments.</td>
                        </tr>
                        <tr v-for="(assessment, idx) in assessments">
                            <td>{{ assessment.name }}</td>
                            <td>{{ assessment.completed }}</td>
                            <td><span class="badge badge-success btn-finger" v-if="assessment.source" @click="downloadUrl(assessment['source_url'])">Source</span></td>
                            <td><span class="badge badge-info btn-finger" @click="changeSlides('tasks', idx)">Tasks</span></td>
                        </tr>
                    </tbody>
                    <tbody v-if="(slide === 'tasks')">
                        <tr v-if="assessments[activeAssessment].tasks.length === 0">
                            <td>There was an error</td>
                            <td>or Assessment has</td>
                            <td> no tasks.</td>
                        </tr>
                        <tr v-for="(task, idx) in assessments[activeAssessment].tasks">
                            <td>{{ task.name }}</td>
                            <td>{{ task.required }}</td>
                            <td>{{ task.completed }}</td>
                            <td><span class="badge badge-secondary btn-finger" @click="setTaskForViewing(task)">View</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <vue-final-modal v-model="showTaskModal" classes="modal-container" content-class="modal-content">
            <task-viewer  @cancel="() => closeModal()" :task="activeTask" v-if="activeTask"></task-viewer>
        </vue-final-modal>
    </div>
</template>

<script>
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'
import TaskViewer from "@/Pages/Candidates/Assessments/Modals/bsTaskViewer";

export default {
    name: "ApplicationSubmissionDetails",
    components: {
        TaskViewer,
        VueFinalModal,
        ModalsContainer
    },
    props: ['assessments'],
    watch: {},
    data() {
        return {
            slide: 'assessments',
            activeAssessment: '',
            showTaskModal:false,
            activeTask: false

        };
    },
    computed: {
        tableTitle() {
            let r = ''
            switch(this.slide) {
                case 'assessments':
                    r = 'Assessments';
                    break;

                case 'tasks':
                    r = 'Tasks';
                    break;
            }

            return r;
        },
        tableColumns() {
            let r = []
            switch(this.slide) {
                case 'assessments':
                    r = ['Assessment', 'Completed', 'Actions'];
                    break;

                case 'tasks':
                    r = ['Task', 'Required', 'Completed', 'Actions'];
                    break;
            }

            return r;
        },

    },
    methods: {
        downloadUrl(url) {
            window.open(url, '_blank');
        },
        changeSlides(slide, pos) {
            this.slide = slide;
            this.activeAssessment = pos;
        },
        setTaskForViewing(task) {
            this.activeTask = task;
            this.showTaskModal = true;
        },
        closeModal() {
            this.activeTask = false;
            this.showTaskModal = false;
        }
    },
    mounted() {}
}
</script>

<style scoped>
    .btn-finger {
        cursor: pointer;
    }
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
        /*background: #fff;*/
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
