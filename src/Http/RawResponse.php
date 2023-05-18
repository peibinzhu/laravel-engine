<?php

declare(strict_types=1);

namespace PeibinLaravel\Engine\Http;

final class RawResponse
{
    /**
     * @var int
     */
    public $statusCode = 0;

    /**
     * @var string[][]
     */
    public $headers = [];

    /**
     * @var string
     */
    public $body = '';

    /**
     * Protocol version.
     * @var string
     */
    public $version = '';

    /**
     * @param string[][] $headers
     */
    public function __construct(int $statusCode, array $headers, string $body, string $version)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->version = $version;
    }
}
