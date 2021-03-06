<?php

namespace PMWD\Bundle\SessionTimeoutBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pmwd_session_timeout');

        $rootNode
            ->children()
                ->integerNode('idle_time')
                    ->min(0)
                    ->defaultValue('0')
                    ->info('After this number of seconds without a request, the session will be invalidated.')
                    ->example('300')
                ->end()
            ->end();
        ;

        return $treeBuilder;
    }
}