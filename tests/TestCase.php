<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected string $base_url = 'http://atomic.test';

    protected array $request_args;

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__.'/../../../../wp-load.php';

        $this->request_args = [
            'headers' => [
                'HX-Request' => true,
                'Atomic-Data' => json_encode([
                    'nonce' => wp_create_nonce('atomic_nonce'),
                    'post_id' => 1,
                ]),
            ],
            'sslverify' => false,
        ];
    }

    public function request(string $method, string $uri): array
    {
        $url = $this->base_url.$uri;

        return wp_remote_request($url, array_merge($this->request_args, ['method' => strtoupper($method)]));
    }
}
