<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-full md:h-screen flex flex-col">
                <div id="pageHead" class="py-8 text-center">
                    <h1 class="text-4xl">{{ assessment.name }}</h1>
                    <p class="text-xl">Focus: <i>{{ assessment.concentration }}</i></p>
                    <span class="badge badge-error">Not Started</span>
                    <br />
                    <small><a href="/portal/dashboard"> Go Back</a></small>
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
                            <div class="stat-desc text-secondary">4 tasks remaining</div>
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
                            <div class="stat-value">Not Installed</div>
                            <div class="stat-desc">Source Code Available!</div>
                            <div class="stat-title truncate">{{ assessment.source.source['file_nickname']}}</div>
                            <div class="stat-desc text-secondary">Download the Installer</div>
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

                <div class="flex flex-col md:flex-row py-12 mx-auto">
                    <div id="tasksSection"   class="card bg-secondary text-primary-content my-8 py-8 mx-4 md:py-0 md:mx-8">
                        <div class="text-center">
                            <h2 class="text-3xl text-center py-4 text-black font-bold">Tasks</h2>
                        </div>

                        <div class="card-body">
                            <table class="table-auto">
                        <thead>
                        <tr>
                            <th class="text-black">Task</th>
                            <th class="text-black">Required</th>
                            <th class="text-black">Status</th>
                            <th class="text-black">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="assessment.tasks.total > 0" v-for="(task, taskName) in assessment.tasks.list">
                            <td class="text-black">{{ taskName }}</td>
                            <td class="text-black">{{ task.required ? 'Yes' : 'No' }}</td>
                            <td><span class="badge badge-error">Incomplete</span></td>
                            <td><button class="badge badge-success">Respond</button></td>
                        </tr>
                        </tbody>
                        </table>
                        </div>
                    </div>
                    <div id="quizzesSection" class="card bg-error text-primary-content my-8 py-8 mx-4 md:py-0 md:mx-8">
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
        </template>
    </basic-ass-layout>
</template>

<script>
import BasicAssLayout from "@/Layouts/BasicBasicBasicAssLayoutAKANothing";

export default {
    name: "AssessmentDashboard",
    components: {
        BasicAssLayout
    },
    props: ['assessment', 'userData'],
    computed: {
        taskPercent() {
            let pct = 0;

            if(this.assessment.tasks.total === 0) {
                pct = 100;
            }
            else {
                // @todo - calculate the actual pct-age completed
            }

            return `${pct}%`
        },
        tasksToComplete() {
            let r = 'No tasks required';

            if(this.assessment.tasks.total > 0) {
                r = `${this.assessment.tasks.total} tasks remaining`
                // @todo - calculate the actual pct-age completed
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
        }
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
