<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Sources
    Route::apiResource('sources', 'SourceApiController');

    // Statuses
    Route::apiResource('statuses', 'StatusApiController');

    // Correctives
    Route::post('correctives/media', 'CorrectiveApiController@storeMedia')->name('correctives.storeMedia');
    Route::apiResource('correctives', 'CorrectiveApiController');
});
