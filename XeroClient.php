<?php

namespace BlackOptic\Bundle\XeroBundle;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
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
            'base_url',
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

        $config = Collection::fromConfig($config, array(), $required);
        parent::__construct($config->get('base_url'), $config);
        $this->addSubscriber(new OauthPlugin($config->toArray()));
    }

    public function setToken($token, $tokenSecret)
    {
        $this->token = $token;
        $this->tokenSecret = $tokenSecret;
        return $this;
    }

}
