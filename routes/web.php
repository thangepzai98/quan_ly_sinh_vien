<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// dashboard
Route::get('/dashboard', 'DashboardController@index');

// subject
Route::get('/subject', 'SubjectController@index');
Route::post('/getDataSubject', 'SubjectController@getDataList');
Route::post('/createSubject', 'SubjectController@create');
Route::get('/getSubjectById/{id}', 'SubjectController@edit');
Route::post('/updateSubject/{id}', 'SubjectController@update');
Route::post('/deleteSubject/{id}', 'SubjectController@delete');

// faculty
Route::get('/faculty', 'FacultyController@index');
Route::post('/getDataFaculty', 'FacultyController@getDataList');
Route::post('/createFaculty', 'FacultyController@create');
Route::get('/getFacultyById/{id}', 'FacultyController@edit');
Route::post('/updateFaculty/{id}', 'FacultyController@update');
Route::post('/deleteFaculty/{id}', 'FacultyController@delete');

// class
Route::get('/class', 'ClassController@index'); 
Route::post('/getDataClass', 'ClassController@getDataList');
Route::post('/createClass', 'ClassController@create');
Route::get('/getClassById/{id}', 'ClassController@edit');
Route::post('/updateClass/{id}', 'ClassController@update');
Route::post('/deleteClass/{id}', 'ClassController@delete');

// lecturer
Route::get('/lecturer', 'LecturerController@index'); 
Route::post('/getDataLecturer', 'LecturerController@getDataList');
Route::post('/createLecturer', 'LecturerController@create');
Route::get('/getLecturerById/{id}', 'LecturerController@edit');
Route::post('/updateLecturer/{id}', 'LecturerController@update');
Route::post('/deleteLecturer/{id}', 'LecturerController@delete');

// assignment
Route::get('/assignment', 'AssignmentController@index'); 
Route::post('/getDataAssignment', 'AssignmentController@getDataList');
Route::post('/createAssignment', 'AssignmentController@create');
Route::get('/getAssignmentById/{id}', 'AssignmentController@edit');
Route::post('/updateAssignment/{id}', 'AssignmentController@update');
Route::post('/deleteAssignment/{id}', 'AssignmentController@delete');

// student
Route::get('/student', 'StudentController@index'); 
Route::post('/getDataStudent', 'StudentController@getDataList');
Route::post('/createStudent', 'StudentController@create');
Route::get('/getStudentById/{id}', 'StudentController@edit');
Route::post('/updateStudent/{id}', 'StudentController@update');
Route::post('/deleteStudent/{id}', 'StudentController@delete');

// score
Route::get('/score', 'ScoreController@index'); 
Route::post('/getDataScore', 'ScoreController@getDataList');
Route::post('/createScore', 'ScoreController@create');
Route::get('/getScoreById/{id}', 'ScoreController@edit');
Route::post('/updateScore/{id}', 'ScoreController@update');
Route::post('/deleteScore/{id}', 'ScoreController@delete');

// study again
Route::get('/studyAgain', 'StudyAgainController@index'); 
Route::post('/getDataStudyAgain', 'StudyAgainController@getDataList');
Route::get('/getStudyAgainExport', 'StudyAgainController@export');

//test
Route::get('/test', 'SubjectController@getDataSubject');

