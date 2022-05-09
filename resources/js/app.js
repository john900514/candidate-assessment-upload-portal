require('./bootstrap');

import { createApp, h } from 'vue';
import ApplicantToDoList from "@/Widgets/ApplicantToDoList";
import ApplicantOpenPositions from "./Widgets/ApplicantOpenPositions";
import UserManagementForm from "./Cms/CustomCrudForms/UserManagementComponent";
import AssessmentTasksTableComponent from "@/Cms/CustomCrudForms/AssessmentTasksTableComponent";
import QuizQuestionsTableComponent from "@/Cms/CustomCrudForms/QuizQuestionsTableComponent";

const el = '#vue-app-wrapper'
const app = createApp({});

app.component('applicant-open-positions', ApplicantOpenPositions)
app.component('applicant-todo-list', ApplicantToDoList)
app.component('create-user-form', UserManagementForm)
app.component('assessment-tasks-table', AssessmentTasksTableComponent)
app.component('quiz-questions-table', QuizQuestionsTableComponent)

app.mount(el)


