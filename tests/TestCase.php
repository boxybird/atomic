<?php

namespace Tests;

use Illuminate\Encryption\Encrypter;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected string $base_url = 'http://atomic.test';

    protected array $request_args;

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__.'/../../../../wp-load.php';

        if (!defined('ATOMIC_TESTS_ENABLED') || ATOMIC_TESTS_ENABLED !== true) {
            fwrite(STDOUT, "Atomic tests are not enabled. Please define ATOMIC_TESTS_ENABLED as true in your wp-config.php.\n");
            exit(1);
        }

        $data = (new Encrypter(ATOMIC_ENCRYPTION_KEY))->encrypt([
            'nonce' => wp_create_nonce('atomic_nonce'),
            'post_id' => 1,
        ]);

        $this->request_args = [
            'headers' => [
                'HX-Request' => true,
                'Atomic-Data' => json_encode(['data' => $data]),
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
