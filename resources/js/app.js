require('./bootstrap');

import { createApp, h } from 'vue';
import ApplicantOpenPositions from "./Widgets/ApplicantOpenPositions";

const el = '#vue-app-wrapper'
const app = createApp({});

app.component('applicant-open-positions', ApplicantOpenPositions)

app.mount(el)


