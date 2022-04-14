<template>
    <table class="table table-responsive-sm table-striped" v-if="positions.length > 0">
        <thead>
        <tr>
            <th>Job Posting</th>
            <th># of Tests</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(position, idx) in positions">
            <td>{{ position.jobTitle }}</td>
            <td>{{ position.assessments }}</td>
            <td><span class="badge" :class="position.statusBadge">{{ position.status }}</span></td>
            <td><button class="badge badge-info">View</button></td>
        </tr>
        </tbody>
    </table>
    <div class="col-12 align-items-center justify-content-center text-center" v-else>
        <p v-if="loading"> Loading... </p>
        <p> Open Positions will appear here as they become available! </p>
    </div>
</template>

<script>
export default {
    name: "ApplicantOpenPositions",
    components: {},
    props: [],
    watch: {},
    data() {
        return {
            loading: false,
            positions: []
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
        }
    },
    mounted() {
        this.fetchUsersAvailableJobs()
    }
}
</script>

<style scoped>

</style>
