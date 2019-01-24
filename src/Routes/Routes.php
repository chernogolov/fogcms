<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is package web routes
|
*/

Route::get('/test', 'Achernogolov\Fogcms\Controllers\TestController@index')->name('test');

Auth::routes();

//LK
Route::match(['get', 'post'], '/', 'Panel\LkController@index')->name('lk')->where('id', '[0-9]+');
Route::match(['get', 'post'], '/new/{id}/{rid?}', 'Panel\LkController@create')->name('lk_new')->where(['id' => '[0-9]+', 'rid' => '[0-9]+']);
Route::match(['get', 'post'], '/{id}', 'Panel\LkController@appeals')->name('lk_list')->where('id', '[0-9]+');
Route::match(['get', 'post'], '/find/{id}/', 'Panel\LkController@find')->name('lk_find')->where('id', '[0-9]+');
Route::match(['get', 'post'], '/view/{id}/{rid}', 'Panel\LkController@view_appeal')->name('view_appeal')->where('rid', '[0-9]+');
Route::match(['get', 'post'], '/close/{id}/{rid}', 'Panel\LkController@close_appeal')->name('close_appeal')->where('rid', '[0-9]+');
Route::get('success/{id}/{rid}', 'Panel\LkController@success')->name('lk_success');
//END LK

//REGISTERS
Route::get('/panel', 'Panel\RecordsController@index')->name('regs');
Route::match(['get', 'post'], '/panel/trash', 'Panel\RecordsController@trash')->name('trash')->middleware('can:view-admin');
Route::match(['get', 'post'], '/panel/new/{id}', 'Panel\RecordsController@create')->name('create_record')->where('id', '[0-9]+');
Route::match(['get', 'post'], '/panel/{id}/{rid}', 'Panel\RecordsController@view')->name('view_record')->where(['id' => '[0-9]+', 'rid' => '[0-9]+']);
Route::match(['get', 'post'], '/panel/edit/{id}/{rid}', 'Panel\RecordsController@edit')->name('edit_record')->where(['id' => '[0-9]+', 'rid' => '[0-9]+']);
Route::match(['get', 'post'], '/panel/{id}/', 'Panel\RecordsController@records')->name('reg_records')->where('id', '[0-9]+');

//appeals statuses
Route::get('/panel/status/{rid}', 'Panel\RecordsController@change_status')->name('change_status')->where(['rid' => '[0-9]+']);

//export & templates
Route::get('/document/{id}/{rid}', 'Panel\RecordsController@document')->name('document')->where(['id' => '[0-9]+', 'rid' => '[0-9]+']);
Route::match(['get', 'post'], '/panel/export/{id}', 'Panel\RecordsController@export')->name('export')->where('id', '[0-9]+');
//END REGISTERS

//COMMENTS
Route::get('/panel/comments/{rid}', 'Panel\CommentsController@getComments')->name('comments')->where('rid', '[0-9]+');
Route::match(['get', 'post'], '/panel/comments/edit/{rid}', 'Panel\CommentsController@editComments')->name('editcomments')->where('rid', '[0-9]+');
Route::match(['get', 'post'], '/panel/comments/delete/{rid}', 'Panel\CommentsController@deleteComments')->name('deletecomments')->where('rid', '[0-9]+');
//END COMMENTS

//OPTIONS
Route::match(['get', 'post'], '/options/account', 'Panel\OptionsController@account')->name('account');
Route::match(['get', 'post'], '/options/notify', 'Panel\OptionsController@notify_regs')->name('notify_regs');
Route::match(['get', 'post'], '/options/{group?}', 'Panel\OptionsController@getdata')->name('options');
//END OPTIONS

//SETTINGS
Route::get('/settings', 'Panel\SettingsController@index')->name('settings')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/reglist/', 'Panel\Settings\RegsController@reglist')->name('reglist')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/reglist/new', 'Panel\Settings\RegsController@create')->name('newreg')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/reglist/{id}', 'Panel\Settings\RegsController@edit')->name('regedit')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/attrs/', 'Panel\Settings\AttrController@attrlist')->name('attrlist')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/attrs/new', 'Panel\Settings\AttrController@create')->name('newattr')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/attrs/{id?}', 'Panel\Settings\AttrController@edit')->name('editattr')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/lists', 'Panel\Settings\ListsController@lists')->name('lists')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/list/{id}', 'Panel\Settings\ListsController@edit')->name('editlist')->middleware('can:view-admin');Route::match(['get', 'post'], '/settings/forms/{id}', 'Panel\Settings\FormsController@edit')->name('editform')->middleware('can:view-admin');
//END SETTINGS

//USERS
Route::match(['get', 'post'],'/users', 'Panel\UsersController@users')->name('users')->middleware('can:view-admin');
Route::match(['get', 'post'],'/users/regs', 'Panel\UsersController@regs')->name('users_regs')->middleware('can:view-admin');
Route::match(['get', 'post'],'/blocking', 'Panel\UsersController@blocking')->name('blocking')->middleware('can:view-admin');
Route::match(['get', 'post'],'/users/new', 'Panel\UsersController@create')->name('new_user')->middleware('can:view-admin');
Route::match(['get', 'post'],'/users/edit/{id}', 'Panel\UsersController@edit')->name('edit_user')->middleware('can:view-admin');
//END USERS

//IMAGES
Route::match(['get', 'post'],'/upload_image', 'ImageController@ajaxUpload')->name('upload_image');
Route::match(['get', 'post'],'/delete_image', 'ImageController@ajaxDelete')->name('delete_image');
//END IMAGES

//???
Route::match(['get', 'post'], '/usearch', 'Panel\UsersController@searchUser')->name('usearch');
Route::match(['get', 'post'], '/reg/{id}', 'Panel\RecordsController@ajaxReg')->name('reg');
Route::match(['get', 'post'], '/search/{id}', 'SearchController@search')->name('search');
Route::match(['get', 'post'], '/record/{id}/{rid}', 'Panel\SearchController@get_record')->name('get_record')->where(['id' => '[0-9]+', 'rid' => '[0-9]+']);
//???

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Кэш очищен.";
});