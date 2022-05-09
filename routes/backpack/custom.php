<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Actions',
], function () { // custom admin routes
    Route::post('edit-account-info/access-token', 'Users\Auth\AccessToken')->name('backpack.account.access-token');
    Route::post('assets/download-installer', 'Assets\DownloadInstallerAction')->name('download-installer');

    Route::post('assets/quizzes/{quiz_id}/questions', 'Assets\Tests\CreateNewQuizQuestion');
    Route::delete('assets/quizzes/{quiz_id}/questions', 'Assets\Tests\DeactivateQuizQuestion');

    Route::post('users/{user_id}/resend-email', 'Users\Auth\ResendWelcomeEmail')->name('user.resend-email');
    Route::get('candidates/user-open-jobs', 'Candidate\Users\FetchOpenJobs');
    Route::post('candidates/submit-application', 'Candidate\Jobs\SubmitJobApplication');

    Route::post('assessments/tasks', 'Candidate\Assessments\Tasks\CreateNewTask');
    Route::put('assessments/tasks', 'Candidate\Assessments\Tasks\ReactivateTask');
    Route::delete('assessments/tasks', 'Candidate\Assessments\Tasks\DeactivateTask');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers',
], function () { // custom admin routes
    Route::get('dashboard', 'DashboardController@index')->name('backpack.dashboard');
    Route::get('assessments', 'Candidates\Assessments\AssessmentViewerController@index')->name('assessments.dashboard');
    Route::get('/assessments/{id}', 'Candidates\Assessments\AssessmentViewerController@show')->name('assessments.view-assessment');
    Route::get('edit-account-info', 'UserAccountController@index')->name('backpack.account.info');
    Route::get('/registration/upload-resume', 'Users\UserRegistrationController@show_resume_uploader')->name('candidates.resume-uploader');
    Route::post('/registration/upload-resume', 'Users\UserRegistrationController@upload_resume')->name('candidates.upload-resume');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin'),
        (array) 'employees-only',
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('users', 'Users\UserManagementCrudController');
    Route::crud('candidates/job-positions', 'Candidates\JobPositionCrudController');
    Route::crud('candidates/assessments', 'Candidates\AssessmentCrudController');
    Route::crud('assets/source-code', 'Assets\SourceCodeCrudController');
    Route::crud('assets/quizzes', 'Assets\QuizCrudController');
});// this should be the absolute last line of this file
