<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-screen flex justify-center items-center">
                <div class="w-4/5 md:w-[50%] border border-secondary">
                    <div class="w-full mb-4">
                        <div id="formHeader" class="text-center mb-4 md:mb-8">
                            <p class="mx-4 mt-4 text-white text-xl">Welcome New Candidate!</p>
                            <small class="m-0 text-white"> Complete the Form to View your Assessment(s)</small>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="flex flex-row justify-between mx-2">
                                <div class="form-group md:w-full">
                                    <label class="text-white">First Name</label>
                                    <input type="text" class="form-control md:w-[95%]" v-model="form['first_name']"/>
                                </div>

                                <div class="form-group md:w-full">
                                    <label class="text-white">Last Name</label>
                                    <input type="text" class="form-control md:w-full" v-model="form['last_name']"/>
                                </div>
                            </div>

                            <div class="w-full mt-4">
                                <div class="form-group mx-2">
                                    <label class="text-white">Email</label>
                                    <input type="text" class="form-control w-full" v-model="form['email']"/>
                                </div>
                            </div>

                            <div class="w-full mt-4">
                                <div class="form-group mx-2">
                                    <label class="text-white">Password</label>
                                    <input type="password" class="form-control w-full" v-model="form['password']"/>
                                </div>
                            </div>

                            <div class="w-full mt-4">
                                <div class="form-group mx-2">
                                    <label class="label cursor-pointer">
                                        <input type="checkbox" checked="checked" class="checkbox text-white" v-model="form['consent']">
                                        <span class="label-text ml-4">Check this box saying you agree this account is for taking a test for qualifying for an interview and everything you see inside our service or submit is proprietary and property of Cape & Bay, LLC and are willing to sign a nondisclosure agreement in acknowledgement.</span>
                                    </label>
                                </div>
                            </div>

                            <div class="w-full mt-4">
                                <div class="mx-2">
                                    <button type="button" class="btn btn-success" @click="submitForm()">Proceed</button>
                                </div>
                            </div>
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
    name: "CompleteRegistration",
    components: {
        BasicAssLayout
    },
    props: ['role', 'email', 'userId', 'firstName', 'lastName'],
    data() {
        return {
            form: {
                email: this.email,
                'first_name': this.firstName,
                'last_name': this.lastName,
                password: '',
                consent: false,
            },
        };
    },
    methods: {
        submitForm() {
            if(this.validateForm()) {
                let payload = this.form;
                payload['status'] = 'candidate';
                payload['user_id'] = this.userId;

                axios.post('/portal/registration', payload)
                    .then(({ data }) => {
                        new Noty({
                            theme: 'sunset',
                            type: 'success',
                            text: "Success! Redirecting you...",
                            timeout: 4000
                        }).show()

                        setTimeout(function() {
                            window.location.href = '/portal/login'
                        }, 1500)
                    })
                    .catch( err => {
                        new Noty({
                            theme: 'sunset',
                            type: 'error',
                            text: "Could not complete your registration!"+err,
                            timeout: 4000
                        }).show()
                    })
            }
        },
        validateForm() {
            let r = false;

            for(let field in this.form) {
                if((this.form[field] === '') || (this.form[field] === false)) {
                    new Noty({
                        theme: 'sunset',
                        type: 'warning',
                        text: "You gotta make sure to add the "+field+"!",
                        timeout: 400
                    }).show()
                    return r;
                    break;
                }
            }

            r = true;

            return r;
        }
    }
}
</script>

<style scoped>

</style>
