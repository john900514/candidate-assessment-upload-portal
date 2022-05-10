<template>
    <table class="table table-responsive-sm table-striped" v-if="positions.length > 0">
        <thead>
        <tr>
            <th>Job Posting</th>
            <th># of Quizzes</th>
            <th># of Tests</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(position, idx) in positions">
            <td>{{ position.jobTitle }}</td>
            <td>{{ position.quizzes }}</td>
            <td>{{ position.assessments }}</td>
            <td><span class="badge" :class="position.statusBadge">{{ position.status }}</span></td>
            <td><button class="badge badge-info" @click="viewJobPositionModal(position)">View</button></td>
        </tr>
        </tbody>
    </table>
    <div class="col-12 align-items-center justify-content-center text-center" v-else>
        <p v-if="loading"> Loading... </p>
        <p> Open Positions will appear here as they become available! </p>
    </div>
    <vue-final-modal v-model="showModal" classes="modal-container" content-class="modal-content">
        <job-position :position="activePosition" @close="closeModal()"></job-position>
    </vue-final-modal>
</template>

<script>
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'
import JobPosition from "@/Modals/ApplicantJobPositionModal";
export default {
    name: "ApplicantOpenPositions",
    components: {
        JobPosition,
        VueFinalModal,
        ModalsContainer
    },
    props: [],
    watch: {},
    data() {
        return {
            loading: false,
            positions: [],
            showModal: false,
            activePosition: false
        };
    },
    computed: {},
    methods: {
        fetchUsersAvailableJobs() {
            let _this = this;
            this.positions = [];
            this.loading = true;
            let headers = {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            };

            axios.get('/portal/candidates/user-open-jobs', {headers})
                .then(({ data }) => {
                    _this.loading = false;
                    _this.positions = data;
                })
                .catch(err => {
                    _this.loading = false;
                    new Noty({
                        type: "error",
                        text: "No Open Positions Available. Try Again Later!",
                        timeout: 7500,
                    })
                })
        },
        viewJobPositionModal(position) {
            console.log('pushing position', position);
            this.activePosition = position;
            this.openModal();
        },
        openModal() {
            this.showModal = true
        },
        closeModal() {
            this.activePosition = false;
            this.showModal = false
        },
    },
    mounted() {
        this.fetchUsersAvailableJobs()
    }
}
</script>

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
    /*padding: 1rem;*/
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    /*background: #fff;*/
}
.modal__title {
    font-size: 1.5rem;
    font-weight: 700;
}
.dark-mode div::v-deep .modal-content {
    border-color: #2d3748;
    background-color: #1a202c;
}
</style>

