<?php

declare(strict_types=1);

namespace PeibinLaravel\Engine\Http;

use PeibinLaravel\Engine\Contracts\Http\ClientInterface;
use PeibinLaravel\Engine\Exceptions\HttpClientException;
use Swoole\Coroutine\Http\Client as HttpClient;

class Client extends HttpClient implements ClientInterface
{
    public function set(array $settings): bool
    {
        return parent::set($settings);
    }

    /**
     * @param string[][] $headers
     */
    public function request(
        string $method = 'GET',
        string $path = '/',
        array $headers = [],
        string $contents = '',
        string $version = '1.1'
    ): RawResponse {
        $this->setMethod($method);
        $this->setData($contents);
        $this->setHeaders($this->encodeHeaders($headers));
        $this->execute($path);
        if ($this->errCode !== 0) {
            throw new HttpClientException($this->errMsg, $this->errCode);
        }
        return new RawResponse(
            $this->statusCode,
            $this->decodeHeaders($this->headers ?? []),
            $this->body,
            $version
        );
    }

    /**
     * @param string[] $headers
     * @return string[][]
     */
    private function decodeHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $name => $header) {
            // The key of header is lower case.
            $result[$name][] = $header;
        }
        if ($this->set_cookie_headers) {
            $result['set-cookie'] = $this->set_cookie_headers;
        }
        return $result;
    }

    /**
     * Swoole engine not support two dimensional array.
     * @param string[][] $headers
     * @return string[]
     */
    private function encodeHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $name => $value) {
            $result[$name] = is_array($value) ? implode(',', $value) : $value;
        }

        return $result;
    }
}
