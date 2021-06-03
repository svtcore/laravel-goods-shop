<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
Route::get('/', function(){
    return redirect()->route('user.main.page');
});
Route::middleware('language')->group(function (){
    Route::get('setlocale/{locale}','LocaleController@changeLanguage')->name('locale')->where('locale', '^[a-z]{2}$');
    
    Route::namespace('user')->group(function () {
        Route::get('/', 'ProductController@index')->name('user.main.page');
        Route::prefix('products')->group(function (){
            Route::get('/search', 'ProductController@search')->name('user.product.search');
            Route::get('{id}', 'ProductController@show')->name('user.product.show')->where('id', '[0-9]+');
        });
        Route::prefix('orders')->group(function (){
            Route::get('/cart', 'OrderController@create')->name('user.order.cart');
            Route::post('/', 'OrderController@store')->name('user.order.store');
            Route::get('/', 'OrderController@index')->middleware('auth')->name('user.orders');
        });
        Route::prefix('categories')->group(function (){
            Route::get('{id}', 'CategoryController@show')->name('user.category.show')->where('id', '[0-9]+');
        });
        Route::prefix('user')->group(function(){
            Route::prefix('auth')->group(function(){
                Route::get('/', ['as' => 'login', 'uses' => 'Auth\LoginController@index']);
                Route::post('/', ['middleware' => 'guest', 'uses' => 'Auth\LoginController@authenticate',])->name('user.login');
                Route::get('logout', ['middleware' => 'auth', 'uses' => 'Auth\LoginController@logout',])->name('user.logout');
            });
        });
    });

    /* manager routes */

    Route::prefix('manager')->namespace('manager')->group(function (){
        Route::get('/', function(){ redirect()->route('manager.orders.status');});
        Route::prefix('auth')->group(function(){
            Route::get('/', 'Auth\LoginController@index')->name('manager.login.page');
            Route::post('/', ['middleware' => 'guest', 'uses' => 'Auth\LoginController@authenticate',])->name('manager.login');
            Route::get('logout', ['middleware' => 'auth:manager', 'uses' => 'Auth\LoginController@logout',])->name('manager.logout');
        });
        Route::prefix('orders')->middleware('auth:manager')->group(function (){
            Route::get('/status/{name}', 'OrderController@get_by_status')->name('manager.orders.status')->where('name', '[A-Za-z]+');
            Route::get('/{id}', 'OrderController@show')->name('manager.order.show')->where('id', '[0-9]+');
            Route::get('{id}/edit', 'OrderController@edit')->name('manager.order.edit')->where('id', '[0-9]+');
            Route::put('{id}', 'OrderController@update')->name('manager.order.update')->where('id', '[0-9]+');
            Route::get('/search', 'OrderController@search')->name('manager.order.search');
        });
    });

    /* admin routes*/

    Route::prefix('admin')->namespace('admin')->group(function (){
        //Route::get('/', function(){ redirect()->route('admin.home');});
        Route::middleware('auth:admin')->group(function (){
            Route::get('/home', 'HomeController@index')->name('admin.home');
            Route::prefix('orders')->group(function(){
                Route::get('/search', 'OrderController@search')->name('admin.order.search');
                Route::get('/', 'OrderController@index')->name('admin.orders');
                Route::get('/{id}', 'OrderController@show')->name('admin.order.show')->where('id', '[0-9]+');
                Route::get('/{id}/edit', 'OrderController@edit')->name('admin.order.edit')->where('id', '[0-9]+');
                Route::delete('/{id}', 'OrderController@destroy')->name('admin.order.delete')->where('id', '[0-9]+');
                Route::put('/{id}', 'OrderController@update')->name('admin.order.update')->where('id', '[0-9]+');
            });
            Route::prefix('users')->group(function(){
                Route::get('/', 'UserController@index')->name('admin.users');
                Route::get('/search', 'UserController@search')->name('admin.user.search');
                Route::get('/{id}', 'UserController@show')->name('admin.user.show')->where('id', '[0-9]+');
                Route::get('/{id}/edit', 'UserController@edit')->name('admin.user.edit')->where('id', '[0-9]+');
                Route::delete('/{id}', 'UserController@destroy')->name('admin.user.delete')->where('id', '[0-9]+');
                Route::put('/{id}', 'UserController@update')->name('admin.user.update')->where('id', '[0-9]+');
                Route::get('/{id}/orders', 'UserController@orders')->name('admin.user.order')->where('id', '[0-9]+');
            });
            Route::prefix('products')->group(function(){
                Route::get('/', 'ProductController@index')->name('admin.products');
                Route::get('/search', 'ProductController@search')->name('admin.product.search');
                Route::get('/new', 'ProductController@create')->name('admin.product.new');
                Route::post('/', 'ProductController@store')->name('admin.product.add');
                Route::get('/{id}/edit', 'ProductController@edit')->name('admin.product.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'ProductController@update')->name('admin.product.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'ProductController@destroy')->name('admin.product.delete')->where('id', '[0-9]+');
            });

            Route::prefix('categories')->group(function(){
                Route::get('/', 'CategoryController@index')->name('admin.categories');
                Route::get('/new', 'CategoryController@create')->name('admin.category.new');
                Route::post('/', 'CategoryController@store')->name('admin.category.add');
                Route::get('/{id}/edit', 'CategoryController@edit')->name('admin.category.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'CategoryController@update')->name('admin.category.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'CategoryController@destroy')->name('admin.category.delete')->where('id', '[0-9]+');
            });

            Route::prefix('managers')->group(function(){
                Route::get('/search', 'ManagerController@search')->name('admin.manager.search');
                Route::get('/', 'ManagerController@index')->name('admin.managers');
                Route::get('/new', 'ManagerController@create')->name('admin.manager.new');
                Route::post('/', 'ManagerController@store')->name('admin.manager.add');
                Route::get('/{id}/edit', 'ManagerController@edit')->name('admin.manager.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'ManagerController@update')->name('admin.manager.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'ManagerController@destroy')->name('admin.manager.delete')->where('id', '[0-9]+');
                Route::get('/{id}/orders', 'ManagerController@orders')->name('admin.manager.orders')->where('id', '[0-9]+');
            });

            Route::prefix('payments')->group(function(){
                Route::get('/', 'PaymentTypeController@index')->name('admin.payments');
                Route::get('/new', 'PaymentTypeController@create')->name('admin.payment.new');
                Route::post('/', 'PaymentTypeController@store')->name('admin.payment.add');
                Route::get('/{id}/edit', 'PaymentTypeController@edit')->name('admin.payment.edit')->where('id', '[0-9]+');
                Route::put('/{id}', 'PaymentTypeController@update')->name('admin.payment.update')->where('id', '[0-9]+');
                Route::delete('/{id}', 'PaymentTypeController@destroy')->name('admin.payment.delete')->where('id', '[0-9]+');
            });
        });
            Route::prefix('auth')->group(function(){
                Route::get('/', 'Auth\LoginController@index')->name('admin.login.page');
                Route::post('/', ['middleware' => 'guest', 'uses' => 'Auth\LoginController@authenticate',])->name('admin.login');
                Route::get('logout', ['middleware' => 'auth:admin', 'uses' => 'Auth\LoginController@logout',])->name('admin.logout');
            });
    });

});



