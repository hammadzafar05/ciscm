<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['namespace'=>'Api','prefix'=>'v1'],function(){

    Route::post('/accounts','AppController@login');
    Route::put('/accounts','AppController@update');
    Route::get('/configs','AppController@setup');
    Route::post('/students','StudentController@create');
    Route::get('/sessions','CourseController@getSessions');
    Route::get('/courses','CourseController@getCourses');
    Route::get('/courses/{id}','CourseController@getSession');
    Route::get('/sessions/{id}','CourseController@getSession');
    Route::get('/articles','ArticlesController@articles');
    Route::get('/articles/{id}','ArticlesController@getArticle');

    Route::get('/posts','BlogController@posts');
    Route::get('/posts/{id}','BlogController@getPost');

    Route::get('/tokens/{id}','StudentController@getToken');
    Route::get('/videos/{id}','CourseController@getVideo');

    Route::post('/password-resets','StudentController@resetPassword');

    Route::group(['middleware'=>'student.api'],function(){
        Route::post('/student-passwords','StudentController@changePassword');
        Route::get('/students/{id}','StudentController@getProfile');
        Route::put('/students/{id}','StudentController@updateProfile');
        Route::post('/profile-photos','StudentController@saveProfilePhoto');
        Route::delete('/profile-photos','StudentController@removeProfilePhoto');

        Route::get('/intros/{id}','CourseController@getIntro');
        Route::get('/classes/{id}','CourseController@getClass');
        Route::get('/lectures/{id}','CourseController@getLecture');

        Route::get('/invoices','InvoiceController@invoices');
        Route::post('/invoices','InvoiceController@storeInvoice');
        Route::get('/invoices/{id}','InvoiceController@getInvoice');

        Route::get('/payment-methods','InvoiceController@paymentMethods');
        Route::get('/payment-methods/{id}','InvoiceController@getPaymentMethod');
        Route::get('/student-courses','CourseController@studentCourses');
        Route::get('/bookmarks','CourseController@bookmarks');
        Route::post('/bookmarks','CourseController@storeBookmark');
        Route::delete('/bookmarks/{id}','CourseController@removeBookmark');
        Route::get('/tests','TestController@tests');
        Route::get('/tests/{id}','TestController@getTest');
        Route::get('/test-results','TestController@results');
        Route::get('/test-results/{id}','TestController@getResult');
        Route::get('/student-tests','TestController@studentTests');
        Route::get('/student-tests/{id}','TestController@getStudentTest');
        Route::post('/student-tests','TestController@createStudentTest');
        Route::put('/student-tests/{id}','TestController@updateStudentTest');

        Route::get('/certificates','CertificateController@certificates');
        Route::get('/certificates/{id}','CertificateController@getCertificate');
        Route::get('/downloads','DownloadController@downloads');
        Route::get('/downloads/{id}','DownloadController@getDownload');
        Route::get('/download-files/{id}','DownloadController@file');

        Route::get('/class-files/{id}','CourseController@classFile');
        Route::post('/student-session-logs','CourseController@loglecture');
        Route::get('/lecture-files/{id}','CourseController@lectureFile');

        Route::get('/assignments','AssignmentsController@assignments');
        Route::get('/assignments/{id}','AssignmentsController@getAssignment');
        Route::post('/assignment-submissions','AssignmentsController@createSubmission');
        Route::put('/assignment-submissions/{id}','AssignmentsController@updateSubmission');
        Route::delete('/assignment-submissions/{id}','AssignmentsController@deleteSubmission');
        Route::get('/revision-notes-sessions','AssignmentsController@revisionNotesSessions');
        Route::get('/revision-notes','AssignmentsController@revisionNotes');
        Route::get('/revision-notes/{id}','AssignmentsController@getRevisionNote');
        Route::get('/discussion-options','DiscussionController@options');
        Route::get('/discussions','DiscussionController@discussions');
        Route::get('/discussions/{id}','DiscussionController@getDiscussion');
        Route::post('/discussions','DiscussionController@createDiscussion');
        Route::delete('/discussions/{id}','DiscussionController@deleteDiscussion');
        Route::post('/discussion-replies','DiscussionController@createDiscussionReply');
        Route::get('/forum-sessions','ForumController@forumSessions');
        Route::get('/forum-topics','ForumController@forumTopics');
        Route::get('/forum-topics/{id}','ForumController@getForumTopic');
        Route::post('/forum-topics','ForumController@createForumTopic');
        Route::delete('/forum-topics/{id}','ForumController@deleteForumTopic');
        Route::get('/forum-posts','ForumController@getForumPosts');
        Route::post('/forum-posts','ForumController@createForumPost');
    });


});


