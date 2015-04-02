<?php

namespace BlackOptic\Bundle\XeroBundle\Tests;

abstract class TestBase extends \PHPUnit_Framework_TestCase
{

    protected $pemFile;

    protected function setUp()
    {
        parent::setUp();

        $this->pemFile = $this->createPrivateKey();
    }

    protected function tearDown()
    {
        parent::tearDown();

        if (file_exists($this->pemFile))
        {
            unlink($this->pemFile);
        }
    }

    protected function createPrivateKey()
    {
        $pemFile =  md5(microtime()) . '.pem';
        $resource = openssl_pkey_new(['digest_alg' => 'sha1', 'private_key_bits' => 1024, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export($resource, $output);

        file_put_contents($pemFile, $output);

        return $pemFile;
   }
}
