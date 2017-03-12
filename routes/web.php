<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// default public route
Route::get('/', 'HomeController@index');

// TESTING Route
Route::get('files/upload', 'TestingController@upload');
Route::post('files/upload', 'TestingController@storeUpload');
Route::get('tests', 'TestingController@index');
Route::get('tests/send-email', 'TestingController@sendEmail');

// End TESTING Route

// Login Route
Route::get('user/login', 'LoginController@showLoginForm');
Route::post('user/login', 'LoginController@doLogin');
Route::get('user/logout', 'LoginController@doLogout');
Route::get('user/reset', 'LoginController@reset');
Route::put('user/reset', 'LoginController@doReset');
Route::get('user/lost', 'LoginController@showLostPassword');
Route::put('user/lost', 'LoginController@doSendPassword');
Route::get('user/reset-password', 'LoginController@doSendPassword');
// End Login Route

// Announces Route
Route::get('announces/', 'AnnouncesController@index');

Route::get('announces/create', 'AnnouncesController@create');
Route::post('announces/create', 'AnnouncesController@store');

Route::get('announces/{id}/edit', 'AnnouncesController@edit');
Route::put('announces/{id}/edit', 'AnnouncesController@update');

Route::delete('announces/{id}', 'AnnouncesController@destroy');

Route::get('announces/{id}', 'AnnouncesController@showSingleList');
// End Announces Route

// Appraisal Route
Route::get('appraisals/', 'AppraisalController@index');

Route::get('appraisals/create', 'AppraisalController@create');
Route::post('appraisals/create', 'AppraisalController@store');

Route::get('appraisals/{id}/edit', 'AppraisalController@edit');
Route::put('appraisals/{id}/edit', 'AppraisalController@update');

Route::delete('appraisals/{id}', 'AppraisalController@destroy');
//End Appraisal Route

// Category Types Route
Route::get('category-types/', 'CategoryTypeController@index');

Route::get('category-types/create', 'CategoryTypeController@create');
Route::post('category-types/create', 'CategoryTypeController@store');

Route::get('category-types/{id}/edit', 'CategoryTypeController@edit');
Route::put('category-types/{id}/edit', 'CategoryTypeController@update');

Route::delete('category-types/{id}', 'CategoryTypeController@destroy');
// End Category Types Route

// Research Types Route
Route::get('research-types/', 'ResearchTypeController@index');

Route::get('research-types/create', 'ResearchTypeController@create');
Route::post('research-types/create', 'ResearchTypeController@store');

Route::get('research-types/{id}/edit', 'ResearchTypeController@edit');
Route::put('research-types/{id}/edit', 'ResearchTypeController@update');

Route::delete('research-types/{id}', 'ResearchTypeController@destroy');
// End Research Types Route


// Research Types Route
Route::get('output-types/', 'OutputTypeController@index');

Route::get('output-types/create', 'OutputTypeController@create');
Route::post('output-types/create', 'OutputTypeController@store');

Route::get('output-types/{id}/edit', 'OutputTypeController@edit');
Route::put('output-types/{id}/edit', 'OutputTypeController@update');

Route::delete('output-types/{id}', 'OutputTypeController@destroy');
// End Research Types Route

// Periods Route
Route::get('periods/', 'PeriodController@index');

Route::get('periods/create', 'PeriodController@create');
Route::post('periods/create', 'PeriodController@store');

Route::get('periods/{id}/edit', 'PeriodController@edit');
Route::put('periods/{id}/edit', 'PeriodController@update');

Route::delete('periods/{id}', 'PeriodController@destroy');
// End Periods Route

// Proposes Route
Route::get('proposes/', 'ProposeController@index');

Route::get('proposes/create', 'ProposeController@create');
Route::post('proposes/create', 'ProposeController@store');

Route::get('proposes/{id}/display', 'ProposeController@display');

Route::get('proposes/{id}/edit', 'ProposeController@edit');
Route::put('proposes/{id}/edit', 'ProposeController@update');

Route::put('proposes/{id}/edit-member', 'ProposeController@updateMember');

Route::get('proposes/{id}/revision', 'ProposeController@revision');
Route::put('proposes/{id}/revision', 'ProposeController@revisionUpdate');

Route::get('proposes/{id}/download/{type}', 'ProposeController@getFile');

Route::get('proposes/{id}/verification', 'ProposeController@verification');
Route::put('proposes/{id}/verification', 'ProposeController@updateVerification');

Route::get('proposes/{id}/print-confirmation', 'ProposeController@printConfirmation');

Route::delete('proposes/{id}', 'ProposeController@destroy');
// End Proposes Route

// Begin Reviewer Route
Route::get('reviewers/', 'ReviewerController@index');

Route::get('reviewers/create', 'ReviewerController@create');
Route::post('reviewers/create', 'ReviewerController@store');

Route::get('reviewers/{id}/edit', 'ReviewerController@edit');
Route::put('reviewers/{id}/edit', 'ReviewerController@update');

Route::get('reviewers/assign', 'ReviewerController@assignList');
Route::get('reviewers/assign/{id}', 'ReviewerController@assign');
Route::post('reviewers/assign/{id}', 'ReviewerController@assignUpdate');
// End Reviewer Route

// Start Review Proposes Route
Route::get('review-proposes/', 'ReviewProposeController@index');
Route::get('review-proposes/{id}/review', 'ReviewProposeController@review');
Route::post('review-proposes/{id}/review', 'ReviewProposeController@reviewUpdate');
Route::get('review-proposes/{id}/print-review', 'ReviewProposeController@printReview');
Route::get('review-proposes/research-list', 'ReviewProposeController@researchList');
Route::get('review-proposes/{id}/research-display', 'ReviewProposeController@researchDisplay');
// End Review Proposes Route

// Start Approve Proposes Route
Route::get('approve-proposes/', 'ApproveProposeController@index');
Route::get('approve-proposes/{id}/approve', 'ApproveProposeController@approve');
Route::put('approve-proposes/{id}/approve', 'ApproveProposeController@approveUpdate');
Route::get('approve-proposes/{id}/display', 'ApproveProposeController@display');
// End Approve Proposes Route

// Start Dedication Route
Route::get('researches/', 'ResearchController@index');
Route::get('researches/{id}/display', 'ResearchController@display');
Route::get('researches/{id}/edit', 'ResearchController@edit');
Route::put('researches/{id}/edit-progress', 'ResearchController@updateProgress');
Route::put('researches/{id}/edit-final', 'ResearchController@updateFinal');
Route::get('researches/{id}/download/{type}', 'ResearchController@getFile');
Route::get('researches/{id}/output', 'ResearchController@output');
Route::get('researches/{id}/output-download', 'ResearchController@getOutputFile');
Route::put('researches/{id}/output-general', 'ResearchController@updateOutputGeneral');
Route::get('researches/approve-list', 'ResearchController@approveList');
Route::get('researches/{id}/approve', 'ResearchController@approveDetail');
Route::put('researches/{id}/approve', 'ResearchController@approveUpdate');
// End Dedication Route

// Reporting Route
Route::get('reports/count-output', 'ReportingController@countOutput');
// End Route

// AJAX Route
Route::get('ajax/periods/get', 'AJAXController@getPeriod');
Route::get('ajax/members/search', 'AJAXController@searchLecturer');
Route::get('ajax/members/lecturerNIDN', 'AJAXController@getLecturerByNIDN');
Route::get('ajax/proposes/getbyscheme', 'AJAXController@getProposesByScheme');
Route::get('ajax/reviewers/get', 'AJAXController@getReviewer');
Route::get('ajax/reviewers/search', 'AJAXController@searchReviewer');
Route::get('ajax/researches/get', 'AJAXController@getResearch');
Route::get('ajax/faculties/get', 'AJAXController@getFaculty');
Route::get('ajax/study-programs/get', 'AJAXController@getStudyProgram');
Route::get('ajax/lecturers/get', 'AJAXController@getLecturer');
Route::get('ajax/outputs/get', 'AJAXController@getOutput');
Route::get('ajax/outputs/get-count', 'AJAXController@getCountOutput');
// End AJAX Route

//Log Viewer Route
Route::group(['middleware' => ['auth','isSuperUser']], function() {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});
//End Log Viewer Route

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api)
{
    $api->group(['namespace' => 'App\Api\V1\Controllers'], function ($api)
    {
        $api->get('researches/search', 'ResearchController@getAllWithDetail');
        $api->get('outputs/count/search', 'OutputController@getCountAcceptedOutput');
    });
});