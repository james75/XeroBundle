<?php

namespace BlackOptic\Bundle\XeroBundle\DependencyInjection;

use BlackOptic\Bundle\XeroBundle\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class BlackOpticXeroExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $privateKeyFile = $config['xero']['private_key'];

        if(!file_exists($privateKeyFile)){
            throw new FileNotFoundException('Unable able to find file: ' . $privateKeyFile);
        }

        $xeroConfig = array(
            'base_url' => $config['xero']['base_url'],
            'consumer_key' => $config['xero']['consumer_key'],
            'consumer_secret' => $config['xero']['consumer_secret'],
            'private_key' => $config['xero']['private_key']
        );

        if($config['xero']['application_type'] == Configuration::APPLICATION_TYPE_PRIVATE){
            $xeroConfig['token'] = $xeroConfig['consumer_key'];
            $xeroConfig['token_secret'] = $xeroConfig['consumer_secret'];
        }

        $container->setParameter('xero_config', $xeroConfig);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }


}