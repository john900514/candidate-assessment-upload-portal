<template>

        <label>Candidate Status</label>
        <select v-model="status" class="form-control col-12" name="status">
            <option value="">User Type</option>
            <option value="candidate">Candidate for Hire</option>
            <option value="employee">Employee</option>
        </select>

</template>

<script>
import CreateNewUser from "./Screens/CreateNewUser";
import CreateNewCandidate from "./Screens/CreateNewCandidate";

export default {
    name: "UserManagementComponent",
    props: ['preStatus'],
    watch: {
        status(status) {
            if(this.canReload) {
                switch(status)
                {
                    case 'candidate':
                    case 'employee':
                        window.location.href = '/portal/users/create?status='+status;
                        break;
                    default:
                        window.location.href = '/portal/users/create';
                }
            } else {
                this.canReload = true;
            }
        }
    },
    data() {
        return {
            canReload: false,
            status: '',
        };
    },
    mounted() {
        if(this.preStatus) {
            if(this.preStatus !== '') {
                this.status = this.preStatus;
            }
            else {
                this.canReload = true;
            }

        }
        else {
            this.canReload = true;
        }
    }
}
</script>

<style scoped>

</style>
