<template>
    <div class="w-full bg-primary p-4">
        <div class="w-full text-center">
            <h1>{{ position.jobTitle }}</h1>
            <p>Status <span :class="'badge '+ position.statusBadge">{{ position.status }}</span></p>
        </div>

        <div class="w-full text-center">
            <p>Job Details</p>
            <p class="m-0 text-dark"><i v-html="jobDescription"></i></p>
        </div>

        <div class="mt-4 mx-auto w-75 row justify-content-between ">
            <div class="card bg-warning col-sm-12 col-md-12 px-0">
                <div class="card-header">Assessments Required</div>
                <div class="card-body" v-if="assessmentData.length < 1">None for this Assessment</div>
                <div class="card-body" v-else>
                    <table class="table table-responsive-sm table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th># Quizzes</th>
                            <th># Tasks</th>
                            <th>Source Code?</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(assessment, idx) in assessmentData">
                                <td>{{ assessment.name }}</td>
                                <td><span class="badge" :class="assessment.badge">{{ assessment.status }}</span></td>
                                <td>0</td>
                                <td>0</td>
                                <td>No</td>
                                <td><button class="badge badge-info">Open</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card bg-blue col-sm-12 col-md-12">
                <div class="card-body text-center row justify-content-between">
                    <button type="button" class="btn btn-outline-success" :disabled="!readyToApply">{{ applyBtnText }}</button>
                    <button type="button" class="btn btn-danger" @click="closeModal()"> Close Modal </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ApplicantJobPositionModal",
    props: ['position'],
    computed: {
        jobDescription() {
            let r = this.position.desc;

            if((r === '') || (r === null) || (r === undefined)) {
                r = 'No Description Available'
            }

            return r;
        },
        applyBtnText() {
            return this.readyToApply
                ? 'Apply For Job'
                : 'Requirements not Completed'
        },
        readyToApply() {
            let r = false;

            return r;
        },
        assessmentData() {
            let r = [];

            if(this.position.assessmentData !== undefined) {
                r = this.position.assessmentData;
            }

            return r
        }
    },
    methods: {
        closeModal() {
            this.$emit('close')
        }
    },
}
</script>

<style scoped>

</style>
