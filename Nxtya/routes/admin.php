<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function () {
    Route::post('login', [App\Http\Controllers\Admin\AdminAuthController::class, "login"]);

    Route::middleware("auth:admin,api-admin")->group(
        function () {
            Route::delete("destroy/{id}", 'App\Http\Controllers\Admin\UsersControlController@destroy'::class);
            Route::resource("users", App\Http\Controllers\Admin\UsersControlController::class);
            Route::delete("users/{id}/force", 'App\Http\Controllers\Admin\UsersControlController@forceDestroy'::class);
            Route::get("users/all/trashed", 'App\Http\Controllers\Admin\UsersControlController@trashed'::class);
            Route::post('logout', [App\Http\Controllers\Admin\AdminAuthController::class, "logout"]);
        }
    );
});

