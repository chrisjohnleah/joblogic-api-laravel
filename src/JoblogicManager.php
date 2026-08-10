<?php

declare(strict_types=1);

namespace ChrisJohnLeah\JoblogicLaravel;

use ChrisJohnLeah\Joblogic\Data\JoblogicCredentials;
use ChrisJohnLeah\Joblogic\JoblogicClient;
use ChrisJohnLeah\Joblogic\JoblogicTokenConnector;
use ChrisJohnLeah\JoblogicLaravel\Contracts\TokenStore;

final readonly class JoblogicManager
{
    public function __construct(private TokenStore $tokens) {}

    /**
     * @param  array<string, mixed>  $values
     */
    public function client(
        array $values,
        string $environment = 'production',
        bool $forceRefresh = false,
    ): JoblogicClient {
        $credentials = JoblogicCredentials::fromArray($values, $environment);
        $key = hash('sha256', implode('|', [
            $environment,
            $credentials->clientId,
            $credentials->tenantId,
            $credentials->identityBaseUrl,
            $credentials->apiBaseUrl,
            $credentials->scope,
        ]));
        $token = $forceRefresh ? null : $this->tokens->get($key);

        if ($token === null) {
            $authenticator = (new JoblogicTokenConnector($credentials))->getAccessToken();
            $token = $authenticator->getAccessToken();
            $expiresAt = $authenticator->getExpiresAt();
            $expiresIn = $expiresAt === null ? 3600 : max(60, $expiresAt->getTimestamp() - now()->timestamp - 60);
            $this->tokens->put($key, $token, $expiresIn);
        }

        return new JoblogicClient($credentials, $token);
    }
}
