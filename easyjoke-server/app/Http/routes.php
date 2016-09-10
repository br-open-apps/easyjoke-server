<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    dd();
    return view('welcome');
});

// http://localhost:8000/images/kayaks.jpg?w=800&mark=logo-sushi.png&markw=350&markx=20&marky=20&markpos=top-right
Route::get('images/{path}', function(League\Glide\Server $server, Illuminate\Http\Request $request, $path){
    $server->outputImage($path, $request->input());
});
