<?php

namespace Contact\Factory;

use Contact\Service\MailSender;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailSenderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL)
    {
        $serviceManager = $container->get('ServiceManager');
        return new MailSender($serviceManager);
    }
}