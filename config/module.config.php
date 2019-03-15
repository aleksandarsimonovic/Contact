<?php

namespace Contact;

use Contact\Factory\ContactControllerFactory;
use Contact\Factory\MailSenderFactory;
use Contact\Service\MailSender;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'contact' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/contact[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'contact' => __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\ContactController::class => ContactControllerFactory::class,
        ],
    ],
    'services' => [
        MailSender::class => MailSenderFactory::class,
    ],
    'recaptcha' => [
        'siteKey' => '',
        'secretKey' => ''
    ],
    // Configuration for your SMTP server (for sending outgoing mail).
    'smtp' => [
        'email'             => 'office@domain.com',
        'name'              => 'localhost.localdomain',
        'host'              => '<hostname>',
        'port'              => 465,
        'connection_class'  => 'plain',
        'connection_config' => [
            'username' => '<user>',
            'password' => '<pass>',
        ],
    ],
];