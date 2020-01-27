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


Auth::routes(['verify' => true]);

Route::redirect('/home', '/');
Route::get('/', 'HomeController@index')->name('home');

Route::name('account.')->prefix('account')->group(function () {
    Route::get('profile', 'AccountController@showProfileForm')->name('profile');
    Route::post('profile', 'AccountController@updateProfile')->name('update-profile');
    Route::get('password', 'AccountController@showPasswordForm')->name('password');
    Route::post('password', 'AccountController@updatePassword')->name('update-password');
});

Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
Route::name('sessions.')->prefix('sessions')->namespace('Sessions')->group(function () {

});

Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
    Route::resource('users', 'UserController', ['except' => ['show', 'create', 'store', 'edit', 'update']]);
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('trash', 'UserController@trash')->name('trash');
        Route::post('{user}/restore', 'UserController@restore')->name('restore');
        Route::delete('{user}/force-destroy', 'UserController@forceDestroy')->name('force-destroy');
        Route::post('{user}/promote-admin', 'UserController@promoteAdmin')->name('promote-admin');
        Route::post('{user}/relegate-admin', 'UserController@relegateAdmin')->name('relegate-admin');
    });
    Route::resource('sessions', 'SessionController', ['except' => ['show', 'create', 'store', 'edit', 'update', 'destroy']]);
});

Route::name('tests.')->prefix('tests')->namespace('Tests')->group(function () {
    Route::any('run', 'RunController@handle')->name('run');
});
