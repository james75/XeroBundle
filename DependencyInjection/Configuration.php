<?php

namespace BlackOptic\Bundle\XeroBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const APPLICATION_TYPE_PUBLIC = 'public';
    const APPLICATION_TYPE_PRIVATE = 'private';

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('xero');
        $node
        ->isRequired()
            ->children()
                ->enumNode('application_type')
                    ->values(array(self::APPLICATION_TYPE_PUBLIC, self::APPLICATION_TYPE_PRIVATE))
                    ->defaultValue(self::APPLICATION_TYPE_PRIVATE)
                ->end()
                ->scalarNode('base_url')
                    ->defaultValue('https://api.xero.com/api.xro/2.0')
                ->end()
                ->scalarNode('consumer_key')
                    ->isRequired()
                ->end()
                ->scalarNode('consumer_secret')
                    ->isRequired()
                ->end()
                ->scalarNode('private_key')
                    ->isRequired()
                ->end()
            ->end()
        ->end();

        return $node;
    }
}