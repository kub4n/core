<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/slide'], function (Router $router) {
    $router->post('/update', 'SlideController@update')
        ->name('api.slide.update')
        ->middleware('token-can:slider.slides.update');

    $router->post('/delete', 'SlideController@delete')
        ->name('api.slide.delete')
        ->middleware('token-can:slider.slides.destroy');

    $router->get('slides-index', [
        'as' => 'api.slider.slide.index',
        'uses' => 'SlideController@index',
//        'middleware' => 'token-can:blog.posts.index',
    ]);

    $router->delete('slides/{slide}', [
        'as' => 'api.slider.slides.destroy',
        'uses' => 'SlideController@destroy',
//        'middleware' => 'token-can:blog.posts.destroy',
    ]);

    $router->post('slides/{slide}', [
        'as' => 'api.slider.slide.find',
        'uses' => 'SlideController@find',
//        'middleware' => 'token-can:blog.posts.edit',
    ]);

    $router->post('slides/{slide}/edit', [
        'as' => 'api.slider.slide.update',
        'uses' => 'SlideController@update',
        //'middleware' => 'token-can:blog.posts.edit',
    ]);
    $router->post('slides', [
        'as' => 'api.slider.slide.store',
        'uses' => 'SlideController@store',
//        'middleware' => 'token-can:blog.posts.create',
    ]);
});

$router->group(['prefix' => '/slider', 'middleware' => 'api.token'], function (Router $router) {
    $router->get('sliders-server-side', [
        'as' => 'api.slider.sliders.indexServerSide',
        'uses' => 'SliderController@indexServerSide',
//        'middleware' => 'token-can:blog.posts.index',
    ]);
    $router->delete('sliders/{slider}', [
        'as' => 'api.slider.sliders.destroy',
        'uses' => 'SliderController@destroy',
//        'middleware' => 'token-can:blog.posts.destroy',
    ]);
    $router->post('sliders', [
        'as' => 'api.slider.sliders.store',
        'uses' => 'SliderController@store',
//        'middleware' => 'token-can:blog.posts.create',
    ]);
    $router->post('sliders/{slider}', [
        'as' => 'api.slider.sliders.find',
        'uses' => 'SliderController@find',
//        'middleware' => 'token-can:blog.posts.edit',
    ]);
    $router->post('sliders/{slider}/edit', [
        'as' => 'api.slider.sliders.update',
        'uses' => 'SliderController@update',
//        'middleware' => 'token-can:blog.posts.edit',
    ]);
});
