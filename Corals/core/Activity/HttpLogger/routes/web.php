<?php

Route::group(['prefix' => ''], function () {
    Route::resource('http-logs', 'HttpLoggersController')->only(['index', 'show']);
});
