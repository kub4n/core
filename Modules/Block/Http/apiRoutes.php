<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/block', 'middleware' => 'api.token'], function (Router $router) {
    $router->get('blocks', [
        'as' => 'api.block.block.index',
        'uses' => 'BlockController@index',
        'middleware' => 'token-can:block.blocks.index',
    ]);
    $router->get('blocks-server-side', [
        'as' => 'api.block.block.indexServerSide',
        'uses' => 'BlockController@indexServerSide',
        'middleware' => 'token-can:block.blocks.index',
    ]);
    $router->delete('blocks/{block}', [
        'as' => 'api.block.block.destroy',
        'uses' => 'BlockController@destroy',
        'middleware' => 'token-can:block.blocks.destroy',
    ]);
    $router->post('blocks', [
        'as' => 'api.block.block.store',
        'uses' => 'BlockController@store',
        'middleware' => 'token-can:block.blocks.create',
    ]);
    $router->post('blocks/{block}', [
        'as' => 'api.block.block.find',
        'uses' => 'BlockController@find',
        'middleware' => 'token-can:block.blocks.edit',
    ]);
    $router->post('blocks/{block}/edit', [
        'as' => 'api.block.block.update',
        'uses' => 'BlockController@update',
        'middleware' => 'token-can:block.blocks.edit',
    ]);
});
