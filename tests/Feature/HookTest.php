<?php

test('add_action hooks', function ($method, $endpoint) {
    $response = $this->request($method, $endpoint);

    $code = $response['response']['code'];
    $body = $response['body'];

    expect($code)
        ->toBe(200)
        ->and($body)
        ->not()->toBeEmpty()
        ->toMatchSnapshot();
})->with([
    ['method' => 'get', 'endpoint' => '/atomic/v1/test-hook'],
    ['method' => 'post', 'endpoint' => '/atomic/v1/test-hook'],
    ['method' => 'put', 'endpoint' => '/atomic/v1/test-hook'],
    ['method' => 'patch', 'endpoint' => '/atomic/v1/test-hook'],
    ['method' => 'delete', 'endpoint' => '/atomic/v1/test-hook'],
    ['method' => 'get', 'endpoint' => '/atomic/v1/test-builder-hook'],
    ['method' => 'post', 'endpoint' => '/atomic/v1/test-builder-hook'],
    ['method' => 'put', 'endpoint' => '/atomic/v1/test-builder-hook'],
    ['method' => 'patch', 'endpoint' => '/atomic/v1/test-builder-hook'],
    ['method' => 'delete', 'endpoint' => '/atomic/v1/test-builder-hook'],
]);
