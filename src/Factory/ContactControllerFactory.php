<?php

namespace Contact\Factory;

use Doctrine\ORM\EntityManager;
use Contact\Controller\ContactController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ContactControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL)
    {
        /**
         * @var EntityManager
         * $entityManager
         */
        $entityManager = $container->get(EntityManager::class);

        $serviceManager = $container->get('ServiceManager');

        return new ContactController($serviceManager, $entityManager);
    }
}