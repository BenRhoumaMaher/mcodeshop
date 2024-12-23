<?php

namespace App\Logger;

use Psr\Log\LoggerInterface;

class AuthLogger
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function logLoginSuccess(
        string $username
    ): void {
        $this->logger->info(
            sprintf("User '%s' logged in successfully.", $username)
        );
    }

    public function logLoginFailure(
        string $username
    ): void {
        $this->logger->warning(
            sprintf("Failed login attempt for user '%s'.", $username)
        );
    }

    public function logUserRegistration(string $username, string $email): void
    {
        $this->logger->info(
            sprintf("New user registered: %s (%s)", $username, $email)
        );
    }
}
