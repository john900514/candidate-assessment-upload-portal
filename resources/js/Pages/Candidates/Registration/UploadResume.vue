<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-screen flex justify-center items-center">
                <div class="w-4/5 md:w-[50%]">
                    <div class="w-full mb-4">
                        <div id="formHeader" class="text-center mb-4 md:mb-8">
                            <p class="mx-4 mt-4 text-white text-xl">Time to Upload your Resume!</p>
                            <small class="m-0 text-white"> Uploading your resume gives you access to your assessment(s) and helps us to know your skillset!</small>
                            <br />
                            <small class="m-0 text-white"> (Size Limit: 5MB. Accepted File Types: pdf, docx)</small>
                        </div>

                        <div class="w-full flex flex-col">
                            <DropZone class="drop-area" @files-dropped="addFiles" #default="{ dropZoneActive }" v-show="files.length == 0">
                                <div class="flex flex-row justify-center mx-2  border border-secondary py-12">
                                    <label for="file-input">
                                    <span v-if="dropZoneActive">
                                        <span>Drop Them Here</span>
                                        <span class="smaller"> to add them</span>
                                    </span>

                                        <span v-else>
                                        <span>Drag Your Files Here</span>
                                        <span class="smaller">
                                            or <strong><em class="cursor-pointer">click here</em></strong> to select files
                                        </span>
                                    </span>

                                        <input type="file" id="file-input" multiple @change="onInputChange" class="hidden"/>
                                    </label>
                                </div>
                            </DropZone>

                            <div class="flex flex-col justify-center mx-2 mt-8 border border-white py-4" v-show="files.length > 0">
                                <ul class="text-center">
                                    <li v-for="file of files" :key="file.id">{{ file.file.name }}</li>
                                </ul>

                                <div class="text-center mt-8" v-show="!loading">
                                    <button type="button" class="btn btn-success hover:text-white" @click="submitResume()"> Submit Resume</button>
                                    <button type="button" class="btn btn-warning hover:text-white ml-4" @click="files=[]">Start Over </button>
                                </div>
                                <div class="text-center mt-8" v-show="loading">
                                    <p class="text-white">Uploading...</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-8">
                            <button type="button" class="btn btn-error hover:text-white" @click="logout()"> Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </basic-ass-layout>
</template>

<script>
import DropZone from "@/Components/FileUploads/PluginOneDropZone";
import BasicAssLayout from "@/Layouts/BasicBasicBasicAssLayoutAKANothing";

class UploadableFile {
    constructor(file) {
        this.file = file
        this.id = `${file.name}-${file.size}-${file.lastModified}-${file.type}`
        this.url = URL.createObjectURL(file)
        this.status = null
    }
}

export default {
    name: "UploadResume",
    components: {
        DropZone,
        BasicAssLayout
    },
    props: ['userId'],
    data() {
        return {
            loading: false,
            uploadUrl: '/portal/user/resume',
            files: [],
        }
    },
    computed: {},
    methods: {
        addFiles(newFiles) {
            let newUploadableFiles = [...newFiles]
                .map((file) => new UploadableFile(file))
                .filter((file) => !this.fileExists(file.id))

            let fileType = newUploadableFiles[0].file.type;

            switch(fileType) {
                case 'application/pdf':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    if(newUploadableFiles[0].file.size > 5242880) {
                        new Noty({
                            theme: 'sunset',
                            type: 'error',
                            text: 'File size must be less than 5MB'
                        }).show();
                        this.files = [];
                    }
                    else {
                        this.files = this.files.concat(newUploadableFiles)
                    }

                    break;

                default:
                    new Noty({
                        theme: 'sunset',
                        type: 'error',
                        text: 'Unsupported File Type. Use PDF or DOCX'
                    }).show();
                    this.files = [];
            }
            console.log(newUploadableFiles);


        },
        fileExists(otherId) {
            return this.files.some(({ id }) => id === otherId)
        },
        removeFile(file) {
            const index = this.files.indexOf(file)

            if (index > -1) this.files.splice(index, 1)
        },
        onInputChange(e) {
            this.addFiles(e.target.files)
            if(this.files.length > 0) {
                e.target.value = null
            }
        },
        submitResume() {
            let _this = this;
            let file = this.files[0].file;
            let url = '/portal/registration/upload-resume';

            let formData = new FormData()
            formData.append('file', file)

            this.loading = true;
            axios.post(url, formData)
                .then(({ data }) => {
                    new Noty({
                        theme: 'sunset',
                        type: 'success',
                        text: 'Your resume was successfully uploaded! Welcome!'
                    }).show();

                    window.location.href = '/portal/dashboard';
                })
                .catch(({ response }) => {
                    new Noty({
                        theme: 'sunset',
                        type: 'error',
                        text: 'Error occurred - '+response.data
                    }).show();

                    _this.loading = false;

                })
        },
        logout() {
            window.location.href = '/portal/logout';
        }
    },
    mounted() {}
}
</script>

<style scoped>

</style>
