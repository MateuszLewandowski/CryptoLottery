<?php 

namespace Tests\Traits;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait ClientTrait 
{
    private static function getClient(): Client
    {
        return (new Client([
            'base_uri' => 'http://127.0.0.1:35217/',
            'headers' => [
                'API-Token' => 'ecc30c2bc95ff372e91c31b2553df8c3e45dc1a493d3899f2e530676831f8fbc662628bf799e94eaa12baac04b7309d93f866d7cd3576239c8cf1011532f4762',
            ]
        ]));
    }

    private static function post(string $url, array $multipart): ResponseInterface
    {
        return self::getClient()->post($url, $multipart);
    }

    private static function get(string $url): ResponseInterface
    {
        return self::getClient()->get($url);
    }
}