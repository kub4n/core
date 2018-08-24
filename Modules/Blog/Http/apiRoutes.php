<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/blog', 'middleware' => 'api.token'], function (Router $router) {
    $router->get('categories', [
        'as' => 'api.blog.category.index',
        'uses' => 'CategoryController@index',
        'middleware' => 'token-can:blog.categories.index',
    ]);
    $router->get('categories-server-side', [
        'as' => 'api.blog.category.indexServerSide',
        'uses' => 'CategoryController@indexServerSide',
        'middleware' => 'token-can:blog.categories.index',
    ]);
    $router->post('categories-server-side', [
        'as' => 'api.blog.category.indexServerSide',
        'uses' => 'CategoryController@indexServerSide',
        'middleware' => 'token-can:blog.categories.index',
    ]);
    $router->delete('categories/{category}', [
        'as' => 'api.blog.category.destroy',
        'uses' => 'CategoryController@destroy',
        'middleware' => 'token-can:blog.categories.destroy',
    ]);
    $router->post('categories', [
        'as' => 'api.blog.category.store',
        'uses' => 'CategoryController@store',
        'middleware' => 'token-can:blog.categories.create',
    ]);


    $router->post('categories/{category}', [
        'as' => 'api.blog.category.find',
        'uses' => 'CategoryController@find',
        'middleware' => 'token-can:blog.categories.edit',
    ]);
    $router->post('categories/{category}/edit', [
        'as' => 'api.blog.category.update',
        'uses' => 'CategoryController@update',
        'middleware' => 'token-can:blog.categories.edit',
    ]);
    $router->get('posts', [
        'as' => 'api.blog.post.index',
        'uses' => 'PostController@index',
        'middleware' => 'token-can:blog.posts.index',
    ]);
    $router->get('posts-server-side', [
        'as' => 'api.blog.post.indexServerSide',
        'uses' => 'PostController@indexServerSide',
        'middleware' => 'token-can:blog.posts.index',
    ]);
    $router->delete('posts/{post}', [
        'as' => 'api.blog.post.destroy',
        'uses' => 'PostController@destroy',
        'middleware' => 'token-can:blog.posts.destroy',
    ]);
    $router->post('posts', [
        'as' => 'api.blog.post.store',
        'uses' => 'PostController@store',
        'middleware' => 'token-can:blog.posts.create',
    ]);
    $router->post('posts/{post}', [
        'as' => 'api.blog.post.find',
        'uses' => 'PostController@find',
        'middleware' => 'token-can:blog.posts.edit',
    ]);
    $router->post('posts/{post}/edit', [
        'as' => 'api.blog.post.update',
        'uses' => 'PostController@update',
        'middleware' => 'token-can:blog.posts.edit',
    ]);
});
