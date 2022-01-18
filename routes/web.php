<?php

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
// Home

Route::get('/', 'HomeController@show')->name('homepage');
Route::get('api/article/filter', 'HomeController@filter');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup');
Route::post('signup', 'Auth\RegisterController@register');
Route::get('forgot-password', 'Auth\PasswordResetController@showSendLinkForm')->name('showLinkForm');
Route::post('forgot-password', 'Auth\PasswordResetController@sendLink')->name('sendLink');
Route::get('reset', 'Auth\PasswordResetController@showResetPasswordForm')->name('password.reset');
Route::post('reset', 'Auth\PasswordResetController@reset')->name('password.update');

// User
Route::get('user/{id}', 'UserController@show')->name('userProfile');
Route::get('user/{id}/edit', 'UserController@edit')->name('editUser');
Route::put('user/{id}', 'UserController@update')->name('editProfile');
Route::delete('api/user/{id}', 'UserController@delete');
Route::post('user/{id}/report', 'UserController@report');
Route::get('api/user/{id}/suspension', 'UserController@suspension');
Route::get('user/{id}/followed', 'UserController@followed');
Route::get('api/user/{id}/articles', 'UserController@articles');
Route::post('user/{id}/follow', 'UserController@follow');
Route::post('user/{id}/unfollow', 'UserController@unfollow');

// Articles
Route::get('article', 'ArticleController@createForm')->name('createArticle');
Route::post('article', 'ArticleController@create');
Route::get('article/{id}', 'ArticleController@show')->name('article');
Route::get('article/{id}/edit', 'ArticleController@edit')->name('editArticle');
Route::put('article/{id}/edit', 'ArticleController@update');
Route::delete('article/{id}', 'ArticleController@destroy');
Route::get('api/article/{id}/comments', 'ArticleController@comments');

// Content
Route::delete('content/{id}', 'ContentController@removeFeedback');
Route::put('content/{id}', 'ContentController@giveFeedback');

// Admin
Route::get('admin', 'AdminController@show')->name('admin');
Route::get('admin/suspensions', 'AdminController@suspensions');
Route::post('user/{id}/suspend', 'AdminController@suspendUser');
Route::put('user/{id}/unsuspend', 'AdminController@unsuspendUser');
Route::get('admin/reports', 'AdminController@reports');
Route::get('admin/tags', 'AdminController@tags');
Route::put('admin/reports/{id}/close', 'AdminController@closeReport');

// Tag
Route::get('favorite_tags', 'TagController@showUserFavorites');
Route::put('tags/{tag_id}/accept', 'TagController@accept');
Route::put('tags/{tag_id}/reject', 'TagController@reject');
Route::put('tags/{tag_id}/add_favorite', 'TagController@addUserFavorite');
Route::put('tags/{tag_id}/remove_favorite', 'TagController@removeUserFavorite');
Route::get('api/tags/{tag_state}', 'TagController@showFilteredTags')->where('tag_state', '(accepted|rejected|pending)');
Route::delete('tags/{tag_id}', 'TagController@destroy');
Route::post('tags/new', 'TagController@propose');

// Search
Route::get('search', 'SearchController@show')->name('search');
Route::get('api/search/users', 'SearchController@searchUsers');
Route::get('api/search/articles', 'SearchController@searchArticles');

// Notifications
Route::get('api/notifications', 'NotificationController@show');
Route::put('notifications', 'NotificationController@readNotifications');

// Messages
Route::get('messages', 'MessageController@inbox');
Route::get('messages/{id}', 'MessageController@messageThread');
Route::post('messages/{id}', 'MessageController@create');
Route::put('messages/{id}', 'MessageController@readMessages');

// Share
Route::post('/api/share_socials', 'ShareController@shareWidget');
