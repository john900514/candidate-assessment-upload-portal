<?php

Route::group(
    [
        'namespace'  => 'CapeAndBay\ClicksAhoy\Actions',
        'middleware' => 'api',
        'prefix'     => 'api'
    ],
    function() {
        Route::group(['prefix' => 'click-up'], function() {
            Route::get('/workspaces', 'Workspaces\GetWorkspaces')->name('click-up.workspaces');

            Route::get('/spaces', 'Spaces\GetSpaces');
            Route::group(['prefix' => 'space'], function() {
                //Route::post('/', 'Spaces\CreateSpace');
                Route::get('/{space_id}', 'Spaces\GetSpace');
                //Route::put('/{space_id}', 'Spaces\UpdateSpace');
                //Route::delete('/{space_id}', 'Spaces\DeleteSpace');
            });
        });



        //Route::get('auto-log/{uuid}', 'Auth\SSOLoginController@login');
    }
);

Route::group(
    [
        'namespace'  => 'CapeAndBay\ClicksAhoy\Http\Controllers',
        'middleware' => 'api'
    ],
    function() {

    }
);
