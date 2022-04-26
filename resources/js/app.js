require('./bootstrap');

import { createApp, h } from 'vue';
import ApplicantToDoList from "@/Widgets/ApplicantToDoList";
import ApplicantOpenPositions from "./Widgets/ApplicantOpenPositions";
import UserManagementForm from "./Cms/CustomCrudForms/UserManagementComponent";
import AssessmentTasksTableComponent from "@/Cms/CustomCrudForms/AssessmentTasksTableComponent";

const el = '#vue-app-wrapper'
const app = createApp({});

app.component('applicant-open-positions', ApplicantOpenPositions)
app.component('applicant-todo-list', ApplicantToDoList)
app.component('create-user-form', UserManagementForm)
app.component('assessment-tasks-table', AssessmentTasksTableComponent)

app.mount(el)


