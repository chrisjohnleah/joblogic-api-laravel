<?php

declare(strict_types=1);

namespace ChrisJohnLeah\JoblogicLaravel\Contracts;

interface TokenStore
{
    public function get(string $key): ?string;

    public function put(string $key, string $token, int $expiresIn): void;
}
