<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('user.main.page');
});
Route::middleware('language')->group(function () {
    Route::get('setlocale/{locale}', 'LocaleController@changeLanguage')->name('locale')->where('locale', '^[a-z]{2}$');

    Route::namespace('user')->group(function () {
        Route::get('/', 'ProductController@index')->name('user.main.page');
        Route::prefix('products')->group(function () {
            Route::get('/search', 'ProductController@search')->name('user.products.search');
            Route::get('{id}', 'ProductController@show')->name('user.products.show')->where('id', '[0-9]+');
        });
        Route::prefix('orders')->group(function () {
            Route::get('/cart', 'OrderController@create')->name('user.orders.cart');
            Route::post('/', 'OrderController@store')->name('user.orders.store');
            Route::get('/', 'OrderController@index')->middleware('auth')->name('user.orders.index');
        });
        Route::prefix('categories')->group(function () {
            Route::get('{id}', 'CategoryController@show')->name('user.categories.show')->where('id', '[0-9]+');
        });
        Route::prefix('user')->group(function () {
            Route::prefix('auth')->group(function () {
                Route::get('/', ['as' => 'login', 'uses' => 'Auth\LoginController@index']);
                Route::post('/', ['middleware' => 'guest', 'uses' => 'Auth\LoginController@authenticate',])->name('user.login');
                Route::get('logout', ['middleware' => 'auth', 'uses' => 'Auth\LoginController@logout',])->name('user.logout');
            });
        });
    });

    /* manager routes */

    Route::prefix('manager')->namespace('manager')->group(function () {
        Route::get('/', function () {
            redirect()->route('manager.orders.status');
        });
        Route::prefix('auth')->group(function () {
            Route::get('/', 'Auth\LoginController@index')->name('manager.login.page');
            Route::post('/', ['middleware' => 'guest', 'uses' => 'Auth\LoginController@authenticate',])->name('manager.login');
            Route::get('logout', ['middleware' => 'auth:manager', 'uses' => 'Auth\LoginController@logout',])->name('manager.logout');
        });
        Route::prefix('orders')->middleware('auth:manager')->group(function () {
            Route::get('/status/{name}', 'OrderController@get_by_status')->name('manager.orders.status')->where('name', '[A-Za-z]+');
            Route::get('/{id}', 'OrderController@show')->name('manager.orders.show')->where('id', '[0-9]+');
            Route::get('{id}/edit', 'OrderController@edit')->name('manager.orders.edit')->where('id', '[0-9]+');
            Route::put('{id}', 'OrderController@update')->name('manager.orders.update')->where('id', '[0-9]+');
            Route::get('/search', 'OrderController@search')->name('manager.orders.search');
        });
    });

    /* admin routes*/

    Route::prefix('admin')->namespace('admin')->group(function () {
        Route::middleware('auth:admin')->group(function () {
            Route::get('/home', 'HomeController@index')->name('admin.home');
            Route::prefix('orders')->group(function () {
                Route::get('/search', 'OrderController@search')->name('admin.orders.search');
                Route::get('/', 'OrderController@index')->name('admin.orders.index');
                Route::get('/{id}', 'OrderController@show')->name('admin.orders.show')->where('id', '[0-9]+');
                Route::get('/{id}/edit', 'OrderController@edit')->name('admin.orders.edit')->where('id', '[0-9]+');
                Route::delete('/{id}', 'OrderController@destroy')->name('admin.orders.delete')->where('id', '[0-9]+');
                Route::put('/{id}', 'OrderController@update')->name('admin.orders.update')->where('id', '[0-9]+');
            });
            Route::prefix('users')->group(function () {
                Route::get('/', 'UserController@index')->name('admin.users.index');
                Route::get('/search', 'UserController@search')->name('admin.users.search');
                Route::get('/{id}', 'UserController@show')->name('admin.users.show')->where('id', '[0-9]+');
                Route::get('/{id}/edit', 'UserController@edit')->name('admin.users.edit')->where('id', '[0-9]+');
                Route::delete('/{id}', 'UserController@destroy')->name('admin.users.delete')->where('id', '[0-9]+');
                Route::put('/{id}', 'UserController@update')->name('admin.users.update')->where('id', '[0-9]+');
                Route::get('/{id}/orders', 'UserController@orders')->name('admin.users.orders')->where('id', '[0-9]+');
            });
            Route::prefix('products')->group(function () {
                Route::get('/', 'ProductController@index')->name('admin.products.index');
                Route::get('/search', 'ProductController@search')->name('admin.products.search');
                Route::get('/create', 'ProductController@create')->name('admin.products.create');
                Route::post('/', 'ProductController@store')->name('admin.products.store');
                Route::get('/{id}/edit', 'ProductController@edit')->name('admin.products.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'ProductController@update')->name('admin.products.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'ProductController@destroy')->name('admin.products.delete')->where('id', '[0-9]+');
            });

            Route::prefix('categories')->group(function () {
                Route::get('/', 'CategoryController@index')->name('admin.categories.index');
                Route::get('/create', 'CategoryController@create')->name('admin.categories.create');
                Route::post('/', 'CategoryController@store')->name('admin.categories.store');
                Route::get('/{id}/edit', 'CategoryController@edit')->name('admin.categories.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'CategoryController@update')->name('admin.categories.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'CategoryController@destroy')->name('admin.categories.delete')->where('id', '[0-9]+');
            });

            Route::prefix('managers')->group(function () {
                Route::get('/search', 'ManagerController@search')->name('admin.manager.search');
                Route::get('/', 'ManagerController@index')->name('admin.managers.index');
                Route::get('/create', 'ManagerController@create')->name('admin.managers.create');
                Route::post('/', 'ManagerController@store')->name('admin.managers.store');
                Route::get('/{id}/edit', 'ManagerController@edit')->name('admin.managers.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'ManagerController@update')->name('admin.managers.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'ManagerController@destroy')->name('admin.managers.delete')->where('id', '[0-9]+');
                Route::get('/{id}/orders', 'ManagerController@orders')->name('admin.managers.orders')->where('id', '[0-9]+');
            });

            Route::prefix('payments')->group(function () {
                Route::get('/', 'PaymentTypeController@index')->name('admin.payments.index');
                Route::get('/create', 'PaymentTypeController@create')->name('admin.payments.create');
                Route::post('/', 'PaymentTypeController@store')->name('admin.payments.store');
                Route::get('/{id}/edit', 'PaymentTypeController@edit')->name('admin.payments.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'PaymentTypeController@update')->name('admin.payments.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'PaymentTypeController@destroy')->name('admin.payments.delete')->where('id', '[0-9]+');
            });
        });
        Route::prefix('auth')->group(function () {
            Route::get('/', 'Auth\LoginController@index')->name('admin.login.page');
            Route::post('/', ['middleware' => 'guest', 'uses' => 'Auth\LoginController@authenticate',])->name('admin.login');
            Route::get('logout', ['middleware' => 'auth:admin', 'uses' => 'Auth\LoginController@logout',])->name('admin.logout');
        });
    });
});
