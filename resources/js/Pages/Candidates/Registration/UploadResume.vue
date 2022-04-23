<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-full flex justify-center items-center">
                <div class="w-4/5 md:w-[50%] border border-secondary">
                    <div class="w-full mb-4">
                        <div id="formHeader" class="text-center mb-4 md:mb-8">
                            <p class="mx-4 mt-4 text-white text-xl">Time to Upload your Resume!</p>
                            <small class="m-0 text-white"> Uploading your resume gives you access to your assessment(s) and helps us to know your skillset!</small>
                        </div>

                        <div class="w-full flex flex-col">
                            <div class="flex flex-row justify-center mx-2">
                                <p> Upload Plugin Goes Here!</p>
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
    name: "UploadResume",
    components: {
        BasicAssLayout
    },
    props: [],
    data() {
        return {}
    },
    computed: {},
    methods: {},
    mounted() {}
}
</script>

<style scoped>

</style>
