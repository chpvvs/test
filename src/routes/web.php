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

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/login', 'Demos\Admin\AdminLoginController@showLoginForm')->name('admin.login');
        Route::post('/login', 'Demos\Admin\AdminLoginController@login')->name('admin.login.submit');
        Route::get('/logout', 'Demos\Admin\AdminLoginController@logout')->name('admin.logout');
        Route::get('/', 'Demos\Admin\AdminController@index')->name('admin.index');
        Route::post('/password-reset', 'Demos\Admin\AdminLoginController@passwordReset')->name('admin.login.password-reset');
    });
});

Route::group(['middleware' => ['web', 'auth:account']], function () {
    Route::prefix('admin')->group(function () {
        Route::resource('settings', 'Demos\Admin\AdminSettingsController');
        Route::post('/do-crop', 'Demos\Admin\AdminController@doCrop')->name('admin.doCrop');
        Route::post('/crop', 'Demos\Admin\AdminController@crop')->name('admin.crop');
        Route::get('/crop', 'Demos\Admin\AdminController@index');
        Route::resource('cabinet', 'Demos\Admin\AdminCabinetController');
        Route::post('/settings/add-language', 'Demos\Admin\AdminSettingsController@addLanguage')->name('admin.addLanguage');
        Route::post('/settings/save-language', 'Demos\Admin\AdminSettingsController@saveLanguage')->name('admin.saveLanguage');
        Route::post('/settings/ord-languages', 'Demos\Admin\AdminSettingsController@ordLanguages')->name('admin.ordLanguages');
        Route::post('/settings/save-params', 'Demos\Admin\AdminSettingsController@saveParams')->name('admin.saveParams');
        Route::post('/cabinet/crop', 'Demos\Admin\AdminCabinetController@postCrop')->name('admin.cabinet.crop');
        Route::post('/cabinet/update', 'Demos\Admin\AdminCabinetController@postUpdate')->name('admin.cabinet.update');
        Route::post('/cabinet/upload-avatar', 'Demos\Admin\AdminCabinetController@postuploadAvatar')->name('admin.cabinet.upload-avatar');
        Route::post('/cabinet/change-login', 'Demos\Admin\AdminCabinetController@postchangeLogin')->name('admin.cabinet.change-login');
        Route::post('/cabinet/change-password', 'Demos\Admin\AdminCabinetController@postchangePassword')->name('admin.cabinet.change-password');
        Route::post('/cabinet/delete-avatar', 'Demos\Admin\AdminCabinetController@postdeleteAvatar')->name('admin.cabinet.delete-avatar');


        Route::post('/cabinet/getIndex', 'Demos\Admin\AdminCabinetController@getindex');
        Route::get('/users/list', 'Demos\Admin\AdminUsersController@list');

    });
});