<?php

declare(strict_types=1);

namespace BoxyBird\Atomic;

use Exception;
use Illuminate\Encryption\Encrypter;

final class Atomic
{
    private const MATCHED_RULE_PATTERN = 'atomic/v1/([a-zA-Z_-]+)?$';

    private const NONCE_NAME = 'atomic_nonce';

    private static ?self $instance = null;

    private ?string $request = null;

    private array $atomicRequestData = [];

    private ?Encrypter $encrypter = null;

    public static function init(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->setEncrypter();

        add_action('init', [$this, 'registerApiEndpoint']);
        add_filter('redirect_canonical', [$this, 'redirectCanonical'], 10, 2);
        add_action('send_headers', [$this, 'sendHeaders']);
        add_action('template_redirect', [$this, 'templateRedirect']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    private function setEncrypter(): void
    {
        if (!defined('ATOMIC_ENCRYPTION_KEY')) {
            throw new Exception(
                __CLASS__.' cannot find constant ATOMIC_ENCRYPTION_KEY. Must be set in wp-config.php as 16 character random string.'
            );
        }

        $this->encrypter = new Encrypter(ATOMIC_ENCRYPTION_KEY);
    }

    public function registerApiEndpoint(): void
    {
        add_rewrite_rule(self::MATCHED_RULE_PATTERN, 'index.php', 'top');
    }

    public function redirectCanonical($redirect_url, $requested_url): string
    {
        if (!$this->isHtmxRequest()) {
            return $redirect_url;
        }

        // Remove the trailing slash from $requested_url to prevent unnecessary 301 redirect
        return rtrim($requested_url, '/');
    }

    public function sendHeaders(): void
    {
        if (!$this->isHtmxRequest()) {
            return;
        }

        try {
            $data = json_decode(stripslashes_deep($_SERVER['HTTP_ATOMIC_DATA']), true)['data'] ?? null;
            $this->atomicRequestData = $this->encrypter->decrypt($data);
        } catch (Exception) {
            //
        }

        $this->validNonce();

        do_action('atomic/headers');
    }

    public function templateRedirect(): void
    {
        if (!$this->isHtmxRequest()) {
            return;
        }

        $this->handleActions();
    }

    public function enqueue(): void
    {
        wp_enqueue_script('atomic-script', ATOMIC_URL.'js/atomic.js', [], null, true);
        wp_enqueue_script('atomic-htmx-script', ATOMIC_URL.'js/htmx.js', ['atomic-script'], '2.0.4', true);

        $data = $this->encrypter->encrypt([
            'nonce' => wp_create_nonce(self::NONCE_NAME),
            'post_id' => get_the_ID(),
        ]);

        wp_localize_script('atomic-script', 'atomicData', [
            'data' => $data,
        ]);
    }

    private function isHtmxRequest(): bool
    {
        global $wp;

        if ($wp->matched_rule !== self::MATCHED_RULE_PATTERN || !isset($_SERVER['HTTP_HX_REQUEST'])) {
            return false;
        }

        $this->request = $wp->request;

        return true;
    }

    private function validNonce(): void
    {
        if (wp_verify_nonce($this->atomicRequestData['nonce'] ?? -1, self::NONCE_NAME)) {
            header('HTTP/1.1 200 OK');
            return;
        }

        header('HTTP/1.1 403 Forbidden');
        exit;
    }

    private function handleActions(): void
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $handle = explode('/', $this->request)[2] ?? null;
        $hook_name = "atomic/{$method}/{$handle}";

        $post_id = (int) $this->atomicRequestData['post_id'];

        do_action($hook_name, $post_id);
        exit;
    }
}