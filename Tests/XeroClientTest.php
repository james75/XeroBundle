<?php

namespace BlackOptic\Bundle\XeroBundle\Tests;

use BlackOptic\Bundle\XeroBundle\XeroClient;

class XeroClientTest extends TestBase
{
    protected $options;

    protected function setUp()
    {
        parent::setUp();

        $this->options = array(
            'base_url' => 'https://api.xero.com/api.xro/2.0',
            'consumer_key' => '',
            'consumer_secret' => '',
            'private_key' => '',
        );
    }

    public function testInstantiationWithoutKey()
    {
        $this->setExpectedException('\BlackOptic\Bundle\XeroBundle\Exception\FileNotFoundException');
        $client = new XeroClient($this->options);
    }

    public function testInstantiationWithKey()
    {
        $this->options['private_key'] = $this->pemFile;

        $client = new XeroClient($this->options);

        $this->assertNotNull($client);

        // Test set token methods.
        $client->setToken('a', 'b');
    }
}
