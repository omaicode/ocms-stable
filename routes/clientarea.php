<?php

Route::group(['prefix' => 'clientarea', 'as' => 'clientarea.', 'middleware' => 'theme:'.config('theme.currentTheme')], function() {
   Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => 'guest:clients'], function() {
       Route::get('login', 'AuthController@login')->name('login');
       Route::post('login', 'AuthController@loginPost')->name('login.post');

       Route::get('social/login/{provider}', 'AuthController@socialLogin')->name('login.social');
       Route::get('social/callback/{provider}', 'AuthController@socialLoginCallback')->name('login.social-callback');

       Route::get('signup', 'AuthController@signup')->name('signup');
       Route::post('signup', 'AuthController@signupPost')->name('signup.post');
   });

   Route::group(['middleware' => 'auth:clients'], function() {
       Route::get('/logout', 'ClientController@logout')->name('logout');
       Route::get('/', 'ClientController@index')->name('home');
   });
});
