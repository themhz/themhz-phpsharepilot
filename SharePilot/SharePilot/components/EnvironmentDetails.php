<?php
namespace SharePilotV2\Components;

class EnvironmentDetails {
    
    private function getProtocol() {
        // Check if the script is running through a web server
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return 'https';
        }
        return 'http'; // Default to HTTP if not set, although irrelevant in CLI
    }

    public function getClientIp() {
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($keys as $key) {
            if (isset($_SERVER[$key])) {
                $ip = explode(',', $_SERVER[$key])[0];
                return trim($ip);
            }
        }
        return 'UNKNOWN'; // Default when running in CLI or if no IP is found
    }

    public function getServerHost() {
        return gethostname(); // Works both in web and CLI environments
    }

    public function getServerIp() {
        return $_SERVER['SERVER_ADDR'] ?? 'UNKNOWN'; // May not be set in CLI
    }

    public function getDomainName() {
        return $_SERVER['HTTP_HOST'] ?? 'UNKNOWN'; // Likely not set in CLI
    }

    public function getServerSoftware() {
        return $_SERVER['SERVER_SOFTWARE'] ?? 'UNKNOWN'; // Not set in CLI
    }

    public function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN'; // Not set in CLI
    }

    public function getFullUrl() {
        if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
            $protocol = $this->getProtocol();
            return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        return "Not applicable in CLI"; // Full URL does not make sense in CLI
    }

    public function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN'; // Not set in CLI
    }

    public function getBaseUrl() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $protocol = $this->getProtocol();
            return $protocol . '://' . $_SERVER['HTTP_HOST'];
        }
        return "Not applicable in CLI"; // Base URL does not make sense in CLI
    }
}
