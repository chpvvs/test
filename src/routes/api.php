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

Route::group(['middleware' => ['api']], function () {
	Auth::routes();
	Route::prefix('admin')->group(function() {	    
	    //Route::post('/settings/add-language', 'Demos\Admin\AdminSettingsController@addLanguage')->name('admin.addLanguage');
	    Route::resource('settings', 'Demos\Admin\AdminSettingsController');
	});
});

