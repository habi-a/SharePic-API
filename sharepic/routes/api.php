<?php

use Illuminate\Http\Request;

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

Route::post('login', 'API\UsersController@login');
Route::post('register', 'API\UsersController@register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/users', 'API\UsersController@getUsers'); //
    Route::get('/users/my', 'API\UsersController@details');
    Route::get('/users/following', 'API\UsersController@getUserFollowing'); //
    Route::get('/users/followers', 'API\UsersController@getUserFollowers'); //
    Route::get('/users/search/{username}', 'API\UsersController@findUsersByUsername'); //
    Route::get('/users/{id}', 'API\UsersController@getUserByID');
    Route::get('/users/{id}/following', 'API\UsersController@getUserFollowingByID'); //
    Route::get('/users/{id}/followers', 'API\UsersController@getUserFollowersByID'); //
    Route::get('/pictures', 'API\PicturesController@getPictures'); //
    Route::get('/pictures/news', 'API\PicturesController@getPicturesOfFollowed'); //
    Route::get('/pictures/news/{id}', 'API\PicturesController@getPicturesOfFollowedByID'); //
    Route::get('/pictures/users', 'API\PicturesController@getPicturesOfUser'); //
    Route::get('/pictures/users/quantity/{quantity}', 'API\PicturesController@getPicturesOfUserWithQuantity'); //
    Route::get('/pictures/users/{id}', 'API\PicturesController@getPicturesOfUserByID'); //
    Route::get('/pictures/users/{id}/quantity/{quantity}', 'API\PicturesController@getPicturesOfUserbyIDWithQuantity'); //
    Route::get('/pictures/{id}', 'API\PicturesController@getPictureByID');

    Route::post('/pictures', 'API\PicturesController@postPicture');
    Route::post('/follow/{id}', 'API\FollowController@postFollowByID');
    Route::post('/likes/{id}', 'API\LikesController@postLikeByID');
    Route::post('/comments/{id}', 'API\CommentsController@postCommentByID');
    Route::post('/logout','API\UsersController@logout');

    Route::delete('/pictures/{id}', 'API\PicturesController@deletePictureByID');
    Route::delete('/follow/{id}', 'API\FollowController@deleteFollowByID');
    Route::delete('/likes/{id}', 'API\LikesController@deleteLikeByID');
    Route::delete('/comments/{id}', 'API\CommentsController@deleteCommentByID');
});
