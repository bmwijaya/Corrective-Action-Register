<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Sources
    Route::delete('sources/destroy', 'SourceController@massDestroy')->name('sources.massDestroy');
    Route::resource('sources', 'SourceController');

    // Statuses
    Route::delete('statuses/destroy', 'StatusController@massDestroy')->name('statuses.massDestroy');
    Route::resource('statuses', 'StatusController');

    // Correctives
    Route::delete('correctives/destroy', 'CorrectiveController@massDestroy')->name('correctives.massDestroy');
    Route::post('correctives/media', 'CorrectiveController@storeMedia')->name('correctives.storeMedia');
    Route::post('correctives/ckmedia', 'CorrectiveController@storeCKEditorImages')->name('correctives.storeCKEditorImages');
    Route::resource('correctives', 'CorrectiveController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
