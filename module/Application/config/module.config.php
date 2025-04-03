<?php
namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'info' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/info',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'info',
                    ],
                ],
            ],
            'auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:action[/:uid[/:ou]]]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'uid'    => '[a-zA-Z0-9_-]*',
                        'ou'     => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\AuthController::class => Factory\AuthControllerFactory::class,
            Controller\UserController::class => Factory\UserControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\LdapService::class => Factory\LdapServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'layout/layout'      => __DIR__ . '/../view/layout/layout.php',
            'index/index'        => __DIR__ . '/../view/application/index/index.php',
            'error/404'          => __DIR__ . '/../view/error/404.php',
            'error/index'        => __DIR__ . '/../view/error/index.php',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Home',
                'route' => 'home',
            ],
            [
                'label' => 'Info',
                'route' => 'info',
            ],
            [
                'label' => 'Login',
                'route' => 'auth',
            ],
            [
                'label' => 'View User',
                'route' => 'user',
                'action' => 'view',
                'resource' => 'user',
                'privilege' => 'view',
            ],
            [
                'label' => 'List Users',
                'route' => 'user',
                'action' => 'list',
                'resource' => 'user',
                'privilege' => 'list',
            ],
            [
                'label' => 'Create User',
                'route' => 'user',
                'action' => 'create',
                'resource' => 'user',
                'privilege' => 'create',
            ],
            [
                'label' => 'Delete User',
                'route' => 'user',
                'action' => 'delete',
                'resource' => 'user',
                'privilege' => 'delete',
            ],
            [
                'label' => 'Modify User',
                'route' => 'user',
                'action' => 'modify',
                'resource' => 'user',
                'privilege' => 'modify',
            ],
            [
                'label' => 'Logout',
                'route' => 'auth',
                'action' => 'logout',
            ],
        ],
    ],
];