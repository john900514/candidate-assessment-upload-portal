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
    Route::get('candidates/user-open-jobs', 'Candidate\Users\FetchOpenJobs');
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
    Route::get('assessments', 'Candidates\Assessments\AssessmentViewerController@index')->name('backpack.dashboard');
    Route::get('edit-account-info', 'UserAccountController@index')->name('backpack.account.info');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('users', 'Users\UserManagementCrudController');
    Route::crud('candidates/job-positions', 'Candidates\JobPositionCrudController');
    Route::crud('candidates/assessments', 'Candidates\AssessmentCrudController');
    Route::crud('assets/source-code', 'Assets\SourceCodeCrudController');
});// this should be the absolute last line of this file
