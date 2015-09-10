<?php

namespace BlackOptic\Bundle\XeroBundle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use BlackOptic\Bundle\XeroBundle\Exception\FileNotFoundException;

class XeroClient extends Client
{
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $tokenSecret;

    /**
     * {@inheritDoc}
     */
    public function __construct($config = array())
    {
        $required = array(
            'base_uri',
            'consumer_key',
            'consumer_secret',
            'token',
            'token_secret',
        );

        if (!array_key_exists('token', $config)) {
            $config['token'] = & $this->token;
        }

        if (!array_key_exists('token_secret', $config)) {
            $config['token_secret'] = & $this->tokenSecret;
        }

        if (!array_key_exists('base_uri', $config) || !array_key_exists('base_url', $config)) {
            throw new \InvalidArgumentException('base_uri is required in configuration');
        }

        // Use base_uri instead of deprecated base_url configuration.
        if (!array_key_exists('base_uri', $config)) {
            $config['base_uri'] = $config['base_url'];
            unset($config['base_url']);
        }

        // Guzzle no longer supports Collection.
        if (!array_key_exists('consumer_key', $config)) {
            throw new \InvalidArgumentException('consumer_key is required in configuration');
        }

        if (!array_key_exists('consumer_secret', $config)) {
            throw new \InvalidArgumentException('consumer_secret is required in configuration');
        }

        if (empty($config['private_key']) || !file_exists($config['private_key'])) {
            throw new FileNotFoundException('Unable able to find file: ' . $config['private_key']);
        }

        $privateKey = file_get_contents($config['private_key']);

        $config['signature_method'] = 'RSA-SHA1';
        $config['signature_callback'] = function ($baseString) use ($privateKey) {
            $signature = '';
            $privateKeyId = openssl_pkey_get_private($privateKey);
            openssl_sign($baseString, $signature, $privateKeyId);
            openssl_free_key($privateKeyId);
            return $signature;
        };

        if ($config['signature_callback'] === '') {
            throw new \Exception('Could not create signature from key');
        }

        $stack = HandlerStack::create();
        // Create an oauth middleware and push it onto the handler stack.
        $middleware = new Oauth1([
            'consumer_key' => $config['consumer_key'],
            'consumer_secret' => $config['consumer_secret'],
            'token' => $config['token'],
            'token_secret' => $config['token_secret'],
        ]);
        $stack->push($middleware);

        parent::__construct([
            'base_uri' => $config['base_uri'],
            'handler' => $stack,
            'auth' => 'oauth'
        ]);
    }

    public function setToken($token, $tokenSecret)
    {
        $this->token = $token;
        $this->tokenSecret = $tokenSecret;
        return $this;
    }
}
