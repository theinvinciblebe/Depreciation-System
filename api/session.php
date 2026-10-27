<?php
declare(strict_types=1);

/**
 * Serverless Cookie-Based Session Save Handler
 * Provides seamless cross-container session persistence for Vercel without requiring Redis.
 * Cryptographically signed with HMAC-SHA256 to prevent client-side tampering.
 */

class ServerlessCookieSessionHandler implements SessionHandlerInterface {
    private string $secret;
    private string $cookieName;

    public function __construct(string $secret, string $cookieName = 'DEPRESYS_SESS') {
        $this->secret = $secret;
        $this->cookieName = $cookieName;
    }

    public function open(string $path, string $name): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read(string $id): string|false {
        if (!isset($_COOKIE[$this->cookieName]) || empty($_COOKIE[$this->cookieName])) {
            return '';
        }

        $payload = (string)$_COOKIE[$this->cookieName];
        $parts = explode('.', $payload, 2);
        if (count($parts) !== 2) {
            return '';
        }

        [$signature, $data] = $parts;
        $expected = hash_hmac('sha256', $data, $this->secret);
        if (!hash_equals($expected, $signature)) {
            return '';
        }

        $decoded = base64_decode($data, true);
        return $decoded !== false ? $decoded : '';
    }

    public function write(string $id, string $data): bool {
        if (headers_sent()) {
            return false;
        }

        $encoded = base64_encode($data);
        $signature = hash_hmac('sha256', $encoded, $this->secret);
        $payload = $signature . '.' . $encoded;

        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (isset($_SERVER['VERCEL']) || getenv('VERCEL') !== false);

        setcookie($this->cookieName, $payload, [
            'expires'  => time() + (86400 * 7), // 7 days session
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        return true;
    }

    public function destroy(string $id): bool {
        if (!headers_sent()) {
            setcookie($this->cookieName, '', [
                'expires'  => 1,
                'path'     => '/',
                'domain'   => '',
                'secure'   => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }
        unset($_COOKIE[$this->cookieName]);
        return true;
    }

    public function gc(int $max_lifetime): int|false {
        return 0;
    }
}

/**
 * Initializes serverless session handling
 */
function initializeServerlessSession(): void {
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    // Determine secret key for signing session cookies
    $secret = $_ENV['APP_KEY'] ?? getenv('APP_KEY') ?: ($_ENV['SESSION_SECRET'] ?? getenv('SESSION_SECRET') ?: 'depresys_production_secret_key_secure_session_token_32b');

    // Register our serverless session handler
    $handler = new ServerlessCookieSessionHandler($secret);
    session_set_save_handler($handler, true);
}
