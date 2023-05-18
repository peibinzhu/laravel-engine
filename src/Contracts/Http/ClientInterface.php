<?php

declare(strict_types=1);

namespace PeibinLaravel\Engine\Contracts\Http;

use PeibinLaravel\Engine\Http\RawResponse;

interface ClientInterface
{
    public function set(array $settings): bool;

    /**
     * @param string[][] $headers
     */
    public function request(
        string $method = 'GET',
        string $path = '/',
        array $headers = [],
        string $contents = '',
        string $version = '1.1'
    ): RawResponse;
}
