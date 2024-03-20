<?php

/*
|--------------------------------------------------------------------------
| Contact Submit Route
|--------------------------------------------------------------------------
*/
Route::post('guest/contact-submit', 'ContactController@store')->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Localization Route
|--------------------------------------------------------------------------
*/
Route::get('localization/set/{code}', 'LocalizationController@setLanguage')->name('set');

/*
|--------------------------------------------------------------------------
| Pages Route
|--------------------------------------------------------------------------
*/
//Route::get('/c/{slug}', 'BlogController@index');
Route::get('{slug?}', 'PageController@index')->where('slug', '^(?!api|'.config('admin.admin_prefix', 'admin').'|assets|guest).*$');
