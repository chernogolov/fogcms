<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is package web routes
|
*/

Route::get('/test', 'Chernogolov\Fogcms\Controllers\TestController@index')->name('test');

Route::get('/home', function() {
    return redirect('/');
});

//LK
Route::match(['get', 'post'], '/', 'Chernogolov\Fogcms\Controllers\LkController@home')->name('lk')->where('id', '[0-9]+')->middleware('web');
Route::match(['get', 'post'], '/new/{id}/{rid?}', 'Chernogolov\Fogcms\Controllers\LkController@create')->name('lk_new')->where(['id' => '[0-9]+', 'rid' => '[0-9]+']);
Route::match(['get', 'post'], '/{id}', 'Chernogolov\Fogcms\Controllers\LkController@appeals')->name('lk_list')->where('id', '[0-9]+');
Route::match(['get', 'post'], '/find/{id}/', 'Chernogolov\Fogcms\Controllers\LkController@find')->name('lk_find')->where('id', '[0-9]+');
Route::match(['get', 'post'], '/view/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\LkController@view_appeal')->name('view_appeal')->where('rid', '[0-9]+');
Route::match(['get', 'post'], '/close/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\LkController@close_appeal')->name('close_appeal')->where('rid', '[0-9]+');
Route::get('success/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\LkController@success')->name('lk_success');

Route::match(['get', 'post'], '/profile', 'Chernogolov\Fogcms\Controllers\Lk\OptionsController@profile')->name('lk-profile')->middleware('web');
Route::match(['get', 'post'], '/profile/accounts', 'Chernogolov\Fogcms\Controllers\Lk\OptionsController@accounts')->name('lk-accounts')->middleware('web');
Route::match(['get', 'post'], '/profile/notifications', 'Chernogolov\Fogcms\Controllers\Lk\OptionsController@notifications')->name('lk-notifications')->middleware('web');
Route::match(['get', 'post'], '/profile/password', 'Chernogolov\Fogcms\Controllers\Lk\OptionsController@password')->name('lk-password')->middleware('web');

Route::match(['get', 'post'], '/profile/change/{id}', 'Chernogolov\Fogcms\Controllers\LkController@account_change')->name('lk-account-change')->where('id', '[0-9]+')->middleware('web');

Route::match(['get', 'post'], '/utilities', 'Chernogolov\Fogcms\Controllers\Lk\DevicesController@utilities')->name('utilities')->middleware('web');
Route::match(['get', 'post'], '/devices', 'Chernogolov\Fogcms\Controllers\Lk\DevicesController@getDevices')->name('get-devices')->middleware('web');
Route::match(['get', 'post'], '/add-devices-values', 'Chernogolov\Fogcms\Controllers\Lk\DevicesController@addDevicesValues')->name('add-devices-values')->middleware('web');
Route::match(['get', 'post'], '/accepted-values', 'Chernogolov\Fogcms\Controllers\Lk\DevicesController@getAcceptedValues')->name('accepted-values')->middleware('web');

Route::match(['get', 'post'], '/support', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@supportCenter')->name('support')->middleware('web');
Route::match(['get', 'post'], '/tickets', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@getTickets')->name('tickets')->middleware('web');
Route::match(['get', 'post'], '/tickets/new', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@newTicket')->name('new-ticket')->middleware('web');
Route::match(['get', 'post'], '/tickets/view/{id}', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@viewTicket')->name('view-ticket')->where('id', '[0-9]+')->middleware('web');
Route::match(['get', 'post'], '/tickets/close/{id}', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@closeTicket')->name('close-ticket')->where('id', '[0-9]+')->middleware('web');
Route::match(['get', 'post'], '/feedback/add/{id}', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@addFeedback')->name('add-feedback')->where('id', '[0-9]+')->middleware('web');
//Route::match(['get', 'post'], '/housetickets', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@getHouseTicket')->name('housetickets')->middleware('web');

Route::match(['get', 'post'], '/finance', 'Chernogolov\Fogcms\Controllers\Lk\FinanceController@financeCenter')->name('finance')->middleware('web');
Route::match(['get', 'post'], '/charges', 'Chernogolov\Fogcms\Controllers\Lk\FinanceController@getCharges')->name('charges')->middleware('web');
Route::match(['get', 'post'], '/payments', 'Chernogolov\Fogcms\Controllers\Lk\FinanceController@getPayments')->name('payments')->middleware('web');

Route::match(['get', 'post'], '/qa/', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@qA')->name('qa')->middleware('web');
Route::get('/news', 'Chernogolov\Fogcms\Controllers\LkController@newsList')->name('news')->middleware('web');
Route::get('/news/{id}', 'Chernogolov\Fogcms\Controllers\LkController@newsItem')->name('news-item')->where('id', '[0-9]+')->middleware('web');

Route::get('/contacts', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@contactList')->name('contacts')->middleware('web');
Route::get('/contacts/{id}', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@contactItem')->name('contact')->where('id', '[0-9]+')->middleware('web');
Route::match(['get', 'post'], '/messages', 'Chernogolov\Fogcms\Controllers\LkController@userMessages')->name('messages')->middleware('web');

Route::get('/documents', 'Chernogolov\Fogcms\Controllers\Lk\SupportController@documentsList')->name('documents')->middleware('web');
//END LK

//REGISTERS
Route::get('/panel', 'Chernogolov\Fogcms\Controllers\RecordsController@index')->name('regs')->middleware('web');
Route::match(['get', 'post'], '/panel/trash', 'Chernogolov\Fogcms\Controllers\RecordsController@trash')->name('trash')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/new/{id}', 'Chernogolov\Fogcms\Controllers\RecordsController@create')->name('create_record')->where('id', '[0-9]+')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\RecordsController@view')->name('view_record')->where(['id' => '[0-9]+', 'rid' => '[0-9]+'])->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/edit/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\RecordsController@edit')->name('edit_record')->where(['id' => '[0-9]+', 'rid' => '[0-9]+'])->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/{id}/', 'Chernogolov\Fogcms\Controllers\RecordsController@records')->name('reg_records')->where('id', '[0-9]+')->middleware('web')->middleware('can:view-regs');

//appeals statuses
Route::get('/panel/status/{rid}', 'Chernogolov\Fogcms\Controllers\RecordsController@change_status')->name('change_status')->where(['rid' => '[0-9]+'])->middleware('web');
Route::match(['get', 'post'], '/panel/onoff/{rid}/{sid}', 'Chernogolov\Fogcms\Controllers\RecordsController@onoff')->name('onoff')->where(['rid' => '[0-9]+'])->middleware('web');
Route::match(['get', 'post'], '/panel/rate/{rid}', 'Chernogolov\Fogcms\Controllers\RecordsController@rate')->name('rate')->where(['rid' => '[0-9]+'])->middleware('web');


//export & templates
Route::get('/document/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\RecordsController@document')->name('document')->where(['id' => '[0-9]+', 'rid' => '[0-9]+'])->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/export/{id}', 'Chernogolov\Fogcms\Controllers\RecordsController@export')->name('export')->where('id', '[0-9]+')->middleware('web')->middleware('can:view-regs');
//END REGISTERS

//COMMENTS
Route::get('/panel/comments/{rid}', 'Chernogolov\Fogcms\Controllers\CommentsController@getComments')->name('comments')->where('rid', '[0-9]+')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/comments/edit/{rid}', 'Chernogolov\Fogcms\Controllers\CommentsController@editComments')->name('editcomments')->where('rid', '[0-9]+')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/panel/comments/delete/{rid}', 'Chernogolov\Fogcms\Controllers\CommentsController@deleteComments')->name('deletecomments')->where('rid', '[0-9]+')->middleware('web')->middleware('can:view-regs');
//END COMMENTS

//OPTIONS
Route::match(['get', 'post'], '/options/account', 'Chernogolov\Fogcms\Controllers\OptionsController@account')->name('account')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/options/notify', 'Chernogolov\Fogcms\Controllers\OptionsController@notify_regs')->name('notify_regs')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/options/{group?}', 'Chernogolov\Fogcms\Controllers\OptionsController@getdata')->name('options')->middleware('web')->middleware('can:view-regs');
//END OPTIONS

//SETTINGS
Route::get('/settings', 'Chernogolov\Fogcms\Controllers\SettingsController@index')->name('settings')->middleware('web')->middleware('can:view-admin');
Route::match(['get', 'post'], '/settings/reglist/', 'Chernogolov\Fogcms\Controllers\Settings\RegsController@reglist')->name('reglist')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/reglist/new', 'Chernogolov\Fogcms\Controllers\Settings\RegsController@create')->name('newreg')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/reglist/{id}', 'Chernogolov\Fogcms\Controllers\Settings\RegsController@edit')->name('regedit')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/attrs/', 'Chernogolov\Fogcms\Controllers\Settings\AttrController@attrlist')->name('attrlist')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/attrs/new', 'Chernogolov\Fogcms\Controllers\Settings\AttrController@create')->name('newattr')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/attrs/{id?}', 'Chernogolov\Fogcms\Controllers\Settings\AttrController@edit')->name('editattr')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/lists', 'Chernogolov\Fogcms\Controllers\Settings\ListsController@lists')->name('lists')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/list/{id}', 'Chernogolov\Fogcms\Controllers\Settings\ListsController@edit')->name('editlist')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'], '/settings/forms/{id}', 'Chernogolov\Fogcms\Controllers\Settings\FormsController@edit')->name('editform')->middleware('web')->middleware('can:view-regs');
//END SETTINGS

//USERS
Route::match(['get', 'post'],'/users', 'Chernogolov\Fogcms\Controllers\UsersController@users')->name('users')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'],'/users/regs', 'Chernogolov\Fogcms\Controllers\UsersController@regs')->name('users_regs')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'],'/blocking', 'Chernogolov\Fogcms\Controllers\UsersController@blocking')->name('blocking')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'],'/users/new', 'Chernogolov\Fogcms\Controllers\UsersController@create')->name('new_user')->middleware('web')->middleware('can:view-regs');
Route::match(['get', 'post'],'/users/edit/{id}', 'Chernogolov\Fogcms\Controllers\UsersController@edit')->name('edit_user')->middleware('web')->middleware('can:view-regs');
//END USERS

//IMAGES
Route::match(['get', 'post'],'/upload_image', 'Chernogolov\Fogcms\Controllers\ImageController@ajaxUpload')->name('upload_image')->middleware('web');
Route::match(['get', 'post'],'/delete_image', 'Chernogolov\Fogcms\Controllers\ImageController@ajaxDelete')->name('delete_image')->middleware('web');
//END IMAGES

//SEACRHES
Route::match(['get', 'post'], '/search/{id}', 'Chernogolov\Fogcms\Controllers\SearchController@search')->name('search')->middleware('web');

//END SEARCHES

//???
Route::match(['get', 'post'], '/usearch', 'Chernogolov\Fogcms\Controllers\UsersController@searchUser')->name('usearch')->middleware('web');
Route::match(['get', 'post'], '/reg/{id}', 'Chernogolov\Fogcms\Controllers\RecordsController@ajaxReg')->name('reg')->middleware('web');
Route::match(['get', 'post'], '/record/{id}/{rid}', 'Chernogolov\Fogcms\Controllers\SearchController@get_record')->name('get_record')->where(['id' => '[0-9]+', 'rid' => '[0-9]+'])->middleware('web');
//???

// EXCHANGE ROUTE SETTINGS
Route::match(['get', 'post'], '/get-devices', 'Chernogolov\Fogcms\Controllers\Exchange\ExchangeErcController@getDevices')->name('get-devices')->middleware('web');
Route::match(['get', 'post'], '/newticket/{id}', 'Chernogolov\Fogcms\Controllers\Exchange\Exchange1CController@newTicket')->name('newticket')->middleware('web');
Route::match(['get', 'post'], '/updatetickets', 'Chernogolov\Fogcms\Controllers\Exchange\Exchange1CController@updateTickets')->name('updatetickets')->middleware('web');

// END EXCHANGE ROUTE SETTINGS

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Кэш очищен.";
});

Route::match(['get'], '/privacy_policy', 'Chernogolov\Fogcms\Controllers\StaticController@privacy_policy')->name('privacy_policy')->middleware('web');
