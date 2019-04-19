<?php

namespace Console\DependencyInjection\Compiler;

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommandPass implements CompilerPassInterface
{
    /**
     * Inject Translator into the all services which implements TranslatorAwareInterface
     *
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has(Application::class)) {
            $definition = $container->findDefinition(Application::class);

            // find all service IDs with the 'console.command' tag
            $taggedServices = $container->findTaggedServiceIds('console.command');

            foreach ($taggedServices as $id => $tags) {
                // add the Sync API data collector to the Command
                $definition->addMethodCall('add', array(new Reference($id)));
            }
        }
    }
}
