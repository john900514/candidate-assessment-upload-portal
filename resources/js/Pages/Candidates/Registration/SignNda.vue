<template>
    <basic-ass-layout>
        <template #content>
            <div class="w-full h-screen flex flex-col justify-center items-center">
                <div id="formHeader" class="text-center mb-4 md:mb-8">
                    <p class="mx-4 mt-4 text-white text-xl">Non-Disclosure Agreement</p>
                    <small class="m-0 text-white"> Read the Doc and Sign at the Bottom. You can download your own copy after signing.</small>
                </div>
                <div class="bg-white h-3/5 w-4/5 sm:w-3/4 overflow-y-scroll text-black">
                    <div class="ml-4 mr-8 mt-12">
                        <p class="text-center">NON-DISCLOSURE AGREEMENT</p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-left: 5pt;text-indent: 36pt;text-align: justify;">This Non-Disclosure Agreement is entered into as of
                            <span class="s1 font-bold">{{ dateBlank }}, {{ yearBlank }}</span>, by and between Cape&amp;Bay, LLC, a Florida limited liability company (“C&amp;B”) and
                            <span class="s1 font-bold">{{ user.firstName }} {{ user.lastName }}</span>, an individual residing at
                            <span class="border">
                                <button type="button" class="btn btn-accent" @click="showSA = 1" v-if="showSA === 0">Street Address</button> <input type="text" v-model="address" class="form-control bg-white" v-if="showSA === 1" placeholder="Street Address" @keyup.enter="showSA = 2" /><span v-if="showSA === 2" class="font-bold cursor-pointer" @click="showSA = 1">{{ address+", " }} </span>
                                <button type="button" class="btn btn-success" @click="showC = 1" v-if="showC === 0">City</button> <input type="text" v-model="city" class="form-control bg-white" v-if="showC === 1" placeholder="City" @keyup.enter="showC = 2" /><span v-if="showC === 2" class="font-bold cursor-pointer" @click="showC = 1">{{ city+", " }} </span>
                                <button type="button" class="btn btn-warning" @click="showSt = 1" v-if="showSt === 0">State</button> <input type="text" v-model="state" class="form-control bg-white" v-if="showSt === 1" placeholder="State" maxlength="2" @keyup.enter="showSt = 2" /><span v-if="showSt === 2" class="font-bold cursor-pointer" @click="showSt = 1">{{ state }} </span>
                                <button type="button" class="btn btn-error" @click="showZ = 1" v-if="showZ === 0">Zip </button> <input type="text" v-model="zip" class="form-control bg-white" v-if="showZ === 1" placeholder="Zip" @keyup.enter="showZ = 2" /><span v-if="showZ === 2" class="font-bold cursor-pointer" @click="showZ = 1">{{ zip }} </span>
                            </span>, of sound mind (”Recipient”), collectively (“Parties”) for the purpose of preventing the unauthorized disclosure of confidential information.
                        </p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-left: 5pt;text-indent: 36pt;text-align: justify;">WHEREAS, in facilitation of the proposed relationship between the parties, the Parties are willing to provide access to certain information under appropriate safeguards;</p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p><p style="padding-left: 5pt;text-indent: 36pt;text-align: justify;">NOW, THEREFORE, in consideration of the promises and mutual covenants and conditions herein contained, Recipient and C&amp;B agree as follows:</p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <ol id="l1">
                            <li data-list-text="1.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Confidential Information.
                                    <span class="p"> C&amp;B will provide recipient with certain information with respect to the Parties in connection with their proposed relationship, including but not limited to; access to C&amp;B proprietary source code, software/web development assessment, and so on. The Parties acknowledge that all information (“Confidential Information”) provided by the Parties and their agents and representatives heretofore or hereafter (other than information which is generally known or readily ascertainable from public or published information or sources), constitutes confidential and proprietary information and trade secrets of C&amp;B and of their subsidiaries.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="2.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Confidentiality
                                    <span class="p">. The Parties agree to keep the Confidential Information confidential and shall instruct and ensure any directors, officers, employees, managers, advisors and agents, in each case to whom the Parties provide the Confidential Information (&quot;Representatives&quot;), will keep the Confidential Information confidential. The Parties shall not disclose Confidential Information to any person or entity (other than its Representatives) or use Confidential Information for any purpose other than for providing products or services to the Parties. The obligations of the Parties hereunder shall include the obligation to take all steps necessary to protect the confidentiality of the Confidential Information and to prevent the disclosure thereof.</span>
                                </p><p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="3.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Copies.
                                    <span class="p"> Recipient represents and ensures that they shall not make copies of said Confidential Information except those necessary to evaluate the Parties proposed business dealings and/or proposals.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="4.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Return of Confidential Information
                                    <span class="p">. The Parties agree that upon the request, the Parties shall promptly return to sender, any and all Confidential Information, including all copies, both paper and electronic, or shall certify that all said copies, have been destroyed. Notwithstanding the foregoing, the Parties and their Representatives shall be permitted to retain, subject to the terms hereof, Confidential Information (and any derivative thereof) as required by law or legal process.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="5.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Disclosure
                                    <span class="p">. In the event that the Parties or any of their Representatives is requested or required by law or legal process (including by oral questions, interrogatories, requests for information, documentation, subpoena or similar process) to disclose any information supplied to it in the course of the Parties’ dealings with one another or one another’s representatives, it is agreed that the Parties or such Representative will, unless legally prohibited, provide the Parties with prompt notice of each such request or requirement, so that  the Parties may seek an appropriate protective order and/or waive the Parties’</span>
                                </p>
                                <p style="padding-top: 3pt;padding-left: 59pt;text-indent: 0pt;text-align: justify;">compliance with the provisions of this Agreement. It is further agreed that if, after reasonable notice to the Parties, in the absence of a protective order or the receipt of a waiver hereunder, the Parties or such Representative is nonetheless, in the opinion of counsel, compelled to disclose information concerning the Parties to another party, the Parties or such Representative may disclose such information to such party without liability hereunder.</p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="6.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Relief Available
                                    <span class="p">. The Parties acknowledge that they may have no adequate remedy at law for violations or breaches of this Agreement and that the damages resulting from any such breach may not always be readily ascertainable in monetary terms. Therefore, in the event of any such violation or breach by the Parties, they will be entitled to seek equitable relief, by way of injunction or otherwise, in addition to additional damages that the Parties may be entitled to recover.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="7.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Interpretation
                                    <span class="p">. Neither this Agreement nor any part of it shall be construed against any party by reason of the fact that such party may principally be responsible for drafting this Agreement or any provision thereof.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="8.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Successors and Assigns; Entire Agreement
                                    <span class="p">. This Agreement shall bind and shall inure to the benefit of all parties and any and all of their successors and assigns whether by merger, consolidation, transfer of substantially all assets or similar transaction. This Agreement represents the entire agreement between the parties hereto with respect to the subject matter hereof and supersedes all prior agreements between the parties.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="9.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Waiver
                                    <span class="p">. A waiver by any party of any of the terms and conditions hereof shall not be construed as a general waiver by such party and such party shall be free to reinstate any such term or condition, with or without notice to the other party.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="10.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Counterparts
                                    <span class="p">. This Agreement may be executed in multiple counterparts, each of which shall be deemed an original and all of which shall constitute a single agreement.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="11.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Governing Law
                                    <span class="p">. This Agreement shall be governed by and be construed in accordance with the laws of the State of Florida without giving effect to the conflict of laws or choice of law principles thereof.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="12.">
                                <p class="s2" style="padding-left: 59pt;text-indent: 36pt;text-align: justify;">Severability
                                    <span class="p">.  If the application of any term or provision of this Agreement, whether in whole or in part, is held by a court of competent jurisdiction to be invalid, void or unenforceable, in general or in any instance, the remainder of this Agreement shall not be affected by such an adjudication and shall continue in full force and effect.</span>
                                </p>
                                <p style="text-indent: 0pt;text-align: left;"><br/></p>
                            </li>
                            <li data-list-text="13.">
                                <p class="s2" style="padding-left: 131pt;text-indent: -36pt;text-align: left;">Amendments
                                    <span class="p">. This Agreement may only be amended in writing.</span>
                                </p>
                            </li>
                        </ol>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p class="s3" style="padding-top: 11pt;padding-left: 170pt;text-indent: 0pt;text-align: center;">[intentionally left blank]</p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p class="s3" style="padding-left: 170pt;text-indent: 0pt;text-align: center;">[signatures on following page]</p>
                        <p style="padding-top: 3pt;padding-left: 41pt;text-indent: 0pt;text-align: left;">IN WITNESS WHEREOF, the parties have executed this Agreement as of the date first written</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">above.</p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-top: 4pt;padding-left: 221pt;text-indent: 0pt;text-align: left;">Cape&amp;Bay, LLC d/b/a C&amp;B</p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-left: 221pt;text-indent: 0pt;text-align: left;">By:<span class="s1 border-b border-b-4 border-black"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-top: 4pt;padding-left: 221pt;text-indent: 0pt;text-align: left;">Name: <span class="font-bold">Angel Gonzalez</span></p>
                        <p style="padding-top: 4pt;padding-left: 221pt;text-indent: 0pt;text-align: left;">Title: <span class="font-bold">Chief Software Architect</span></p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-left: 220pt;text-indent: 0pt;line-height: 1pt;text-align: left;"/>
                        <p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <div style="padding-left: 221pt;text-indent: 0pt;text-align: left;">SIGN
                            <p class="s1 border-b border-b-4 border-black cursor-pointer  w-1/2" @click="showSign = 1" v-if="showSign === 0"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;Click to Sign&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
                            <div v-if="showSign === 1" class="border border-2 border-black w-1/2 h-[100px]">
                                <vue-signature-pad ref="signaturePad" />
                            </div>
                            <div v-if="showSign === 1">
                                <button @click="save" type="button" class="btn btn-success"><i class="las la-check-circle"></i></button>
                                <button @click="showSign = 0" type="button" class="btn btn-error"><i class="las la-skull-crossbones"></i></button>
                            </div>
                            <div v-if="showSign === 2">
                                <img :src="signature.data" class="w-1/2 cursor-pointer" @click="showSign = 1"/>
                            </div>
                        </div>
                        <p style="padding-left: 220pt;text-indent: 0pt;line-height: 1pt;text-align: left;"/><p style="text-indent: 0pt;text-align: left;"><br/></p>
                        <p style="padding-left: 221pt;text-indent: 0pt;text-align: left;">PRINT<span class="s1 border-b border-b-4 border-black ml-12">{{ user.firstName }} {{ user.lastName }}</span></p>
                        <p style="padding-left: 220pt;text-indent: 0pt;line-height: 1pt;text-align: left;"/><p style="text-indent: 0pt;text-align: left;"><br/></p>
                    </div>
                </div>
                <div class="w-full flex flex-row justify-center mt-4">
                    <button type="button" class="btn btn-error mr-4" onclick="window.location.href = '/portal/logout'" :disabled="loading">Logout</button>
                    <!-- <button type="button" :class="downloadBtnClass" v-if="ready">Download</button> -->
                    <button type="button" :class="submitBtnClass" v-if="!ready" @click="submitSigning" :disabled="loading">{{ loading ? 'Uploading...' : 'Submit'}}</button>
                    <button type="button" :class="nextBtnClass" :disabled="!ready" @click="moveOn()">Next > </button>
                </div>
            </div>
        </template>
    </basic-ass-layout>
</template>

<script>
import BasicAssLayout from "@/Layouts/BasicBasicBasicAssLayoutAKANothing";

export default {
    name: "SignNda",
    components: {
        BasicAssLayout,
    },
    props: ['userId', 'dateBlank', 'yearBlank', 'user'],
    data() {
        return {
            address: null,
            city: null,
            state: null,
            zip: null,
            signature: null,
            showSA: 0,
            showC: 0,
            showSt: 0,
            showZ: 0,
            showSign: 0,
            ready: false,
            loading: false
        }
    },
    computed: {
        submitBtnClass() {
            let r = 'btn btn-outline btn-success mr-4'

            if(this.loading) {
                r = 'btn btn-success loading mr-4'
            }

            return r;
        },
        downloadBtnClass() {
            let r = 'btn btn-warning mr-4'

            return r;
        },
        nextBtnClass() {
            let r = 'btn btn-outline btn-accent text-black'

            return r;
        }
    },
    methods: {
        save() {
            this.$refs.signaturePad.lockSignaturePad();
            this.signature = this.$refs.signaturePad.saveSignature('image/png');
            this.showSign = 2;
            console.log('signature encoded - ', this.signature);
        },
        undo() {},
        submitSigning() {
            if((this.address === null) || (this.address === '')) {
                this.errorNoty('Click the Street Address Button and Enter your Home Address.');
                return;
            }

            if((this.city === null) || (this.city === '')) {
                this.errorNoty('Click the City Button and Enter your City of Residence.');
                return;
            }

            if((this.state === null) || (this.state === '')) {
                this.errorNoty('Click the State Button and Enter 2 letter State you live in.');
                return;
            }

            if((this.zip === null) || (this.zip === '')) {
                this.errorNoty('Click the Zip Button and Enter your 5-digit Zip Code.');
                return;
            }

            if((this.signature === null) || (this.address === '')) {
                this.errorNoty('You need to sign the NDA before submitting!');
                return;
            }

            this.loading = true;
            this.someNoty('Saving your resume into your file! One moment please!', 1);

            let payload = {
                address: this.address,
                city: this.city,
                state: this.state,
                zip: this.zip,
                signature: this.signature.data,
            };

            let _this = this;
            axios.post('/portal/registration/sign-nda', payload)
                .then(({ data }) => {
                    _this.ready = true;
                    _this.loading = false;
                    _this.someNoty('Thank you! We have filed your NDA. A copy was emailed to you. Click Next to continue.', 2)
                })
                .catch(({ response }) => {
                    _this.ready = false;
                    _this.loading = false;
                    _this.errorNoty('An error occurred please try again.')
                })
        },
        errorNoty(msg) {
            new Noty({
                theme: 'sunset',
                type: 'error',
                text: msg,
                timeout: 1500
            }).show()
        },
        someNoty(msg, type) {
            new Noty({
                theme: 'sunset',
                type: type === 1 ? 'info' : 'success',
                text: msg,
                timeout: 3500
            }).show()
        },
        moveOn() {
            window.location.href = '/portal/dashboard';
        }
    },
    mounted() {

    }
}
</script>

<style scoped>

</style>
