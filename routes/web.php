<?php

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

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('test', function () {
    // Create a client with a base URI
    $client = new Client(['base_uri' => 'https://covid2019.leetdev.net/wp-json/wp/v2/']);
    $response = $client->get('posts?categories=11&filter[orderby]=date&order=desc&per_page=1');
    // Check if a header exists.
    if ($response->hasHeader('content-type')) {
        $body = $response->getBody();
        $postList = json_decode($body, false);
        if (count($postList)) {
            $url = "https://covid2019.leetdev.net/archives/" . $postList[0]->id;
        }
    }
    
    if ($url) {
        
    }
    $message =  "There is no updated news currently.";
    echo ("No content");
});
