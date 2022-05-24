<template>
    <div class="w-full bg-primary p-4" id="modal">
        <div class="w-full text-center">
            <h2>{{ position.jobTitle }}</h2>
            <p>Status <span :class="'badge '+ position.statusBadge">{{ position.status }}</span></p>
        </div>

        <div class="w-full text-center">
            <p class="m-0">Job Details</p>
            <p class="m-0 text-dark"><i v-html="jobDescription"></i></p>
        </div>

        <div class="mt-4 mx-auto row justify-content-between">
            <div class="card bg-warning col-sm-12 col-md-12 px-0">
                <div class="card-header">Assessments Required</div>
                <div class="card-body" v-if="assessmentData.length < 1">None for this Assessment</div>
                <div class="card-body" v-else>
                    <table class="table table-responsive-sm table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th class="not-sm"># Quizzes</th>
                            <th class="not-sm"># Tasks</th>
                            <th class="not-sm">Source Code?</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(assessment, idx) in assessmentData">
                                <td>{{ assessment.name }}</td>
                                <td><span class="badge" :class="assessment.badge">{{ assessment.status }}</span></td>
                                <td class="not-sm">{{ assessment.quizzes }}</td>
                                <td class="not-sm">{{ assessment.tasks }}</td>
                                <td class="not-sm">{{ assessment.source }}</td>
                                <td><button class="badge badge-info" @click="viewAssessment(assessment.id)">Open</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card bg-blue col-sm-12 col-md-12">
                <div class="card-body text-center row" id="controlPanel">
                    <button type="button" class="btn" :class="applyBtnClass" :disabled="!readyToApply" @click="submitApplication()" v-if="position.status !== 'Applied'">{{ applyBtnText }}</button>
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
        applyBtnClass() {
            return this.readyToApply
                ? 'btn-success'
                : 'btn-outline-success'

        },
        readyToApply() {
            return this.position.status === 'Ready to Apply';
        },
        assessmentData() {
            let r = [];

            if(this.position.assessmentData !== undefined) {
                r = this.position.assessmentData;
            }

            return r
        },

    },
    methods: {
        closeModal() {
            this.$emit('close')
        },
        viewAssessment(id) {
            window.location.href = '/portal/assessments/'+id;
        },
        submitApplication() {
            let payload = {
                'job_id': this.position['job_id']
            };
            axios.post('/portal/candidates/submit-application', payload)
                .then(({ data }) => {
                    new Noty({
                        theme: 'sunset',
                        type: 'success',
                        text: `You have successfully applied for the position! We will review your application and contact you to schedule an interview!`
                    }).show();

                    setTimeout(function() {
                        window.location.reload();
                    }, 2500)

                })
                .catch(({ response }) => {
                    let r =  response.statusText;

                    if(('data' in response) && ('message' in response.data)) {
                        r = response.data.message;
                    }
                    console.log(response);
                    new Noty({
                        theme: 'sunset',
                        type: 'error',
                        text: `An Error occurred. ${r} Please Try Again`
                    }).show();
                })
        }
    },
}
</script>

<style scoped>
    @media screen and (min-width: 769px) {
        #controlPanel {
            justify-content: space-between;
        }
    }
    @media screen and (max-width: 768px) {
        .not-sm {
            display: none;
        }

        #controlPanel {
            justify-content: center;
        }
    }
</style>
