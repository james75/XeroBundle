<?php

namespace BlackOptic\Bundle\XeroBundle\Tests\DependencyInjection;

use BlackOptic\Bundle\XeroBundle\Tests\TestBase;
use BlackOptic\Bundle\XeroBundle\DependencyInjection\BlackOpticXeroExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BlackOpticXeroExtensionTest extends TestBase
{
    protected $options;

    protected function setUp()
    {
        parent::setUp();

        $this->options = array(
          'black_optic_xero' => array(
            'consumer_key' => '',
            'consumer_secret' => '',
            'private_key' => '',
          ),
        );

        $this->container = new ContainerBuilder();
    }

    public function testBadConfig()
    {
        $this->setExpectedException('\BlackOptic\Bundle\XeroBundle\Exception\FileNotFoundException');
        $bundle = new BlackOpticXeroExtension();

        $bundle->load($this->options, $this->container);
    }

    public function testConfig()
    {
        $this->options['black_optic_xero']['private_key'] = $this->pemFile;

        $bundle = new BlackOpticXeroExtension();

        $bundle->load($this->options, $this->container);
    }
}
