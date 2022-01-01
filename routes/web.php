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
Route::get('/', 'HomeController@show');
Route::get('/api/article/filter', 'HomeController@filter');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('signup', 'Auth\RegisterController@register');

// User
Route::get('user/{id}', 'UserController@show')->name('userProfile');
Route::get('user/{id}/edit', 'UserController@edit')->name('editUser');
Route::put('user/{id}', 'UserController@update');
Route::delete('api/user/{id}', 'UserController@delete');
Route::post('user/{id}/report', 'UserController@report');
Route::get('api/user/{id}/suspension', 'UserController@suspension');
Route::get('user/{id}/followed', 'UserController@followed');
Route::get('user/{id}/articles', 'UserController@articles');
Route::post('user/{id}/follow', 'UserController@follow');
Route::post('user/{id}/unfollow', 'UserController@unfollow');

// Articles
Route::get('articles', 'ArticleController@index')->name('articles');
Route::get('article', 'ArticleController@createForm')->name('createArticle');
Route::post('article', 'ArticleController@create');
Route::get('article/{id}', 'ArticleController@show')->name('article');
Route::get('article/{id}/edit', 'ArticleController@edit')->name('editArticle');
Route::put('article/{id}/edit', 'ArticleController@update');
Route::delete('article/{id}', 'ArticleController@destroy');

// Admin
Route::get('admin', 'AdminController@show');
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
Route::get('tags/{tag_state}', 'TagController@showFilteredTags')->where('tag_state', '(accepted|rejected|pending)');
Route::delete('tags/{tag_id}', 'TagController@destroy');
Route::post('tags/new', 'TagController@propose');

// Search
Route::get('search', 'SearchController@show');
Route::get('api/search/users', 'SearchController@searchUsers');
Route::get('api/search/articles', 'SearchController@searchArticles');
