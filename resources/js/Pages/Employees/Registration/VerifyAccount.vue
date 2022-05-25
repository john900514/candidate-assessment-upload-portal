<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-screen flex justify-center items-center">
                <div class="w-4/5 md:w-[50%] border border-secondary">
                    <div class="w-full mb-4">
                        <div id="formHeader" class="text-center mb-4 md:mb-8">
                            <p class="mx-4 mt-4 text-white text-xl">Welcome to the Cape & Bay Developer's Portal!</p>
                            <small class="m-0 text-white">Enter a new password to Verify your account!</small>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="flex flex-row justify-between mx-2">
                                <div class="form-group md:w-full">
                                    <label class="text-white">First Name</label>
                                    <input type="text" class="form-control md:w-[95%]" v-model="user['first_name']" disabled/>
                                </div>

                                <div class="form-group md:w-full">
                                    <label class="text-white">Last Name</label>
                                    <input type="text" class="form-control md:w-full" v-model="user['last_name']" disabled/>
                                </div>
                            </div>

                            <div class="w-full mt-4">
                                <div class="form-group mx-2">
                                    <label class="text-white">Email</label>
                                    <input type="text" class="form-control w-full" v-model="user['email']" disabled/>
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
                                        <span class="label-text ml-4">Check this box because I said so.</span>
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
    name: "VerifyAccount",
    components: {
        BasicAssLayout
    },
    props: ['userId', 'user'],
    data() {
        return {
            form: {
                id: this.userId,
                password: '',
                consent: false,
            },
        };
    },
    methods: {
        submitForm() {
            if(this.validateForm()) {
                let payload = this.form;

                axios.post('/portal/registration/verify-employee', payload)
                    .then(({ data }) => {
                        new Noty({
                            theme: 'sunset',
                            type: 'success',
                            text: "Success! Redirecting you...",
                            timeout: 4000
                        }).show()

                        setTimeout(function() {
                            window.location.href = '/portal/dashboard'
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
    },
    mounted() {}
}
</script>

<style scoped>

</style>
