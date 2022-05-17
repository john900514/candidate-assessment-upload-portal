<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-full md:h-screen flex flex-col" v-if="udata">
                <div id="pageHead" class="py-8 text-center">
                    <h1 class="text-4xl">{{ assessment.name }}</h1>
                    <p class="text-xl">Focus: <i>{{ assessment.concentration }}</i></p>
                    <span class="badge" :class="udata.badge">{{ udata.status }}</span>
                    <br />
                    <small><a href="/portal/dashboard" class="hover:text-success"> {{ '<<'}} Go Back </a></small>
                </div>

                <div class="mx-auto pt-8 ">
                    <div id="widgetsSection" class="stats shadow">
                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <div class="avatar">
                                    <div class="w-16 rounded-full">
                                        <p class="text-accent text-6xl"><i class="las la-truck-monster"></i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-value">{{ taskPercent }}</div>
                            <div class="stat-title">Tasks: {{ assessment.tasks.total }}</div>
                            <div class="stat-desc text-secondary">{{ tasksToComplete }}</div>
                        </div>

                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <div class="avatar">
                                    <div class="w-16 rounded-full">
                                        <p class="text-error text-6xl"><i class="las la-scroll"></i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-value">{{ quizPercent }}</div>
                            <div class="stat-title">Quizzes {{ assessment.quizzes.total }}</div>
                            <div class="stat-desc text-secondary">{{ quizzesToTake }}</div>
                        </div>

                        <div class="stat extra-w" v-if="assessment.source['has_source']">
                            <div class="stat-figure text-secondary">
                                <div class="avatar">
                                    <div class="w-16 rounded-full">
                                        <p class="text-success text-6xl"><i class="las la-cloud-download-alt"></i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-value"> {{ udata.sourceInstalled ? '' : 'Not '}} Installed</div>
                            <div class="stat-desc" v-if="!udata.sourceInstalled">Source Code Available!</div>
                            <div class="stat-title truncate">{{ assessment.source.source['file_nickname']}}</div>
                            <div class="stat-desc text-secondary" v-if="!udata.sourceInstalled">Download the Installer</div>
                        </div>
                    </div>
                </div>

                <div class="mx-auto pt-8 md:hidden" v-if="assessment.source['has_source']">
                    <div id="mobileDownload" class="stats shadow">
                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <div class="avatar">
                                    <div class="w-16 rounded-full">
                                        <p class="text-warning text-6xl"><i class="las la-cloud-download-alt"></i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="stat-value">Not Installed</div>
                            <div class="stat-desc">Source Code Available!</div>
                            <div class="stat-title truncate">{{ assessment.source.source['file_nickname']}}</div>
                            <div class="stat-desc text-secondary">Download the Installer</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-col py-12 mx-auto" :class="(assessment.quizzes.total > 0) ? 'md:flex-row' : 'md:flex-col'">
                    <div id="tasksSection"   class="card bg-secondary text-primary-content my-8 py-8 mx-4 md:py-0 md:mx-8" :class="(assessment.quizzes.total > 0) ? '' : 'md:w-[100vh]'">
                        <div class="text-center">
                            <h2 class="text-3xl text-center py-4 text-black font-bold">Tasks</h2>
                        </div>

                        <div class="card-body">
                            <table class="table-auto">
                        <thead>
                        <tr>
                            <th class="text-black text-left">Task</th>
                            <th class="text-black text-left">Required</th>
                            <th class="text-black text-left">Status</th>
                            <th class="text-black text-left">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="assessment.tasks.total > 0" v-for="(task, taskName) in assessment.tasks.list">
                            <td class="text-black">{{ taskName }}</td>
                            <td class="text-black">{{ task.required ? 'Yes' : 'No' }}</td>
                            <td><span class="badge" :class="badgeColor(taskName)">{{ badgeText(taskName) }}</span></td>
                            <td><button class="badge badge-success hover:badge-info" @click="showTask(task)">Respond</button></td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                    </div>
                    <div id="quizzesSection" class="card bg-error text-primary-content my-8 py-8 mx-4 md:py-0 md:mx-8" :class="(assessment.tasks.total > 0) ? '' : 'md:w-[100vh]'" v-if="assessment.quizzes.total > 0">
                        <div class="text-center">
                            <h2 class="text-3xl text-center py-4 text-black font-bold">Quizzes</h2>
                        </div>
                        <div class="card-body">
                            <p v-if="assessment.quizzes.total === 0" class="text-center">No Quizzes Required</p>
                            <table class="table-auto p-4" v-if="assessment.quizzes.total > 0">
                                <thead>
                                <tr>
                                    <th>Quiz Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>The Sliding Mr. Bones (Next Stop, Pottersville)</td>
                                    <td><span class="badge badge-error">Incomplete</span></td>
                                    <td><button class="badge badge-success">Start</button></td>
                                </tr>
                                <tr>

                                </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-error">
                <vue-final-modal v-model="showTaskModal" classes="modal-container" content-class="modal-content">
                    <task-viewer @cancel="() => closeModal()" :task="activeTask" @status-change="taskStatusChange" :user-status="udata['task_statuses'][activeTask.task_name]" v-if="activeTask"></task-viewer>
                </vue-final-modal>
                <vue-final-modal v-model="showQuizModal" classes="modal-container" content-class="modal-content">
                    <quiz-viewer @cancel="() => closeModal()"></quiz-viewer>
                </vue-final-modal>
            </div>
        </template>
    </basic-ass-layout>
</template>

<script>
import TaskViewer from "@/Pages/Candidates/Assessments/Modals/TaskViewer";
import QuizViewer from "@/Pages/Candidates/Assessments/Modals/QuizViewer";

import BasicAssLayout from "@/Layouts/BasicBasicBasicAssLayoutAKANothing";
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'

export default {
    name: "AssessmentDashboard",
    components: {
        QuizViewer,
        TaskViewer,
        BasicAssLayout,
        VueFinalModal,
        ModalsContainer
    },
    props: ['assessment', 'userData'],
    data() {
        return {
            showQuizModal: false,
            showTaskModal: false,
            activeTask: false,
            udata: false,
        };
    },
    computed: {
        taskPercent() {
            console.log('taskPercent', this.assessment);
            let pct = 0;

            if(this.assessment.tasks.total === 0) {
                pct = 100;
            }
            else {
                // @todo - calculate the actual pct-age completed
                let total = this.assessment.tasks.total;
                let required = 0;
                let finished = 0;
                for(let x in this.assessment.tasks.list) {
                    let task = this.assessment.tasks.list[x];
                    if(task.required) {
                        required++;

                        let status = this.udata['task_statuses'][task['task_name']]
                        if(status.status === 'Complete') {
                            finished++;
                        }
                    }
                }

                let rawPct = (finished / required) * 100;
                pct = rawPct.toFixed(0);
            }

            return `${pct}%`
        },
        tasksToComplete() {
            let r = 'No tasks required';

            if(this.assessment.tasks.total > 0) {
                let required = 0;
                let finished = 0;
                for(let x in this.assessment.tasks.list) {
                    let task = this.assessment.tasks.list[x];
                    if(task.required) {
                        // @todo - deduct from required tasks that are complete
                        let status = this.udata['task_statuses'][task['task_name']]
                        if(status.status !== 'Complete') {
                            required++;
                        }

                    }
                }

                r = `${required} tasks remaining`
            }

            return r
        },
        quizPercent() {
            let pct = 0;

            if(this.assessment.quizzes.total === 0) {
                pct = 100;
            }
            else {
                // @todo - calculate the actual pct-age completed
            }

            return `${pct}%`
        },
        quizzesToTake() {
            let r = 'No quizzes required';

            if(this.assessment.quizzes.total > 0) {
                // @todo - calculate the actual pct-age completed
            }

            return r
        },

    },
    methods: {
        showQuiz(quiz) {
            this.showQuizModal = true;
        },
        showTask(task) {
            this.activeTask = task;
            this.showTaskModal = true;
        },
        closeModal() {
            this.showQuizModal = false;
            this.showTaskModal = false;
        },
        taskStatusChange(status) {
            let url = '/portal/assessments/tasks/status';
            let payload = {
                'task_name': this.activeTask.task_name,
                status: status.status,
                'assessment_id': this.assessment.id
            };

            if(('explanation' in status) && (status['explanation'] !== '')) {
                payload['explanation'] = status.explanation;
                url = `${url}/complete`;
            }

            let _this = this;
            // Axios to the server to update the status
            axios.post(url, payload)
                .then(({ data }) => {
                   _this.udata = data.userData;
                    //this.udata['task_statuses'][this.activeTask.task_name]['status'] = status;
                    //this.udata['status'] = 'Started';
                    //this.udata['badge'] = 'badge-info';
                })
                .catch(({ response }) => {
                    new Noty({
                        theme: 'sunset',
                        type: 'error',
                        text: response.status
                    }).show()


                })


        },
        badgeText(taskName) {
            let r = 'Fuck You';

            if(this.udata) {
                if(taskName in this.udata['task_statuses']) {
                    r = this.udata['task_statuses'][taskName].status;
                }
            }
            return r;
            //return udata['task_statuses'][this.active_task.taskName]['status']
        },
        badgeColor(taskName) {
            let r = 'Fuck You';

            if('task_statuses' in this.udata) {
                //console.log(this.udata['task_statuses']);
                if(taskName in this.udata['task_statuses']) {
                    r = this.udata['task_statuses'][taskName].badge;
                }
            }
            return r;
            //return udata['task_statuses'][this.active_task.taskName]['status']
        },
    },
    mounted() {
        this.udata = this.userData;
    },
}
</script>

<style scoped>
    @media screen and (max-width: 768px){
        .extra-w {
            display: none;
        }
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
