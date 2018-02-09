<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Rpc')->group(function () {
    Route::prefix('db')->group(function () {
        Route::prefix('get')->group(function () {
            
            Route::get('modelList', 'DBController@getModelList');
            
            Route::post('modelInfo', 'DBController@getModelInfo');
        });
    });
    
    Route::prefix('repo')->group(function () {
        Route::prefix('get')->group(function () {
            
            Route::get('repositoryList', 'RepoController@getRepositoryList');
            
            Route::post('repositoryInfo', 'RepoController@getRepositoryInfo');
            
            Route::post('functionInfo', 'RepoController@getFunctionInfo');
        });
    });
    
    Route::prefix('service')->group(function () {
        Route::prefix('get')->group(function () {
            
            Route::get('serviceList', 'ServiceController@getServiceList');
            
            Route::post('serviceInfo', 'ServiceController@getServiceInfo');
            
            Route::post('apiInfo', 'ServiceController@getApiInfo');
            
            Route::post('callInfo', 'ServiceController@getCallInfo');
        });
    });
});


