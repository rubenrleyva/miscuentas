<?php

use App\Providers\RouteServiceProvider;
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

Auth::routes();

## Al inicio de la aplicación
Route::get('/', function () {

    ## comprobamos si se encuentra login
    Route::middleware(['auth'])->group(function(){
        return view('home'); ## devolvemos a la la página home
    });

    ## en caso contrario devolvemos a la página de login
    return redirect()->route('login');
});


## si se encuentra login
Route::middleware(['auth'])->group(function(){

    ## permitimnos el paso a las diferentes páginas
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/donutgraphic', 'PaymentController@donutGraphic');
    Route::get('/barsgraphic', 'PaymentController@barsGraphic');
    Route::get('/payments', 'PaymentController@index');
    Route::post('/pay', 'PaymentController@store')->name('pay');
    Route::get('/delete/{pago}', 'PaymentController@destroy')->name('delete');

});

