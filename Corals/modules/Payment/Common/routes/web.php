<?php

Route::group(['prefix' => 'payments'], function () {
    Route::get('settings', 'PaymentsController@settings');
    Route::post('settings', 'PaymentsController@saveSettings');
});

Route::get('my-invoices', 'InvoicesController@myInvoices');
Route::post('invoices/bulk-action', 'InvoicesController@bulkAction');
Route::resource('invoices', 'InvoicesController');
Route::get('invoices/{invoice}/download', 'InvoicesController@download');
Route::post('invoices/{invoice}/send-invoice', 'InvoicesController@sendInvoice');
Route::post('webhooks/{gateway?}', 'WebhooksController');
Route::post('currencies/bulk-action', 'CurrenciesController@bulkAction');
Route::resource('currencies', 'CurrenciesController');

Route::group(['prefix' => 'tax'], function () {
    Route::resource('tax-classes', 'TaxClassesController');
    Route::resource('tax-classes.taxes', 'TaxesController');

});

Route::post('transactions/bulk-action', 'TransactionsController@bulkAction');
Route::resource('transactions', 'TransactionsController');


Route::group(['prefix' => 'webhook-calls'], function () {
    Route::get('/', 'WebhooksController@webhookCalls');
    Route::post('{webhookCall}/process', 'WebhooksController@Process');
    Route::post('bulk-action', 'WebhooksController@bulkAction');
});



