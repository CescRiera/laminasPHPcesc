<?php
return [
    'ldap' => [
        'host' => 'zend-ceriam.clotfje.net',
        'username' => "cn=admin,dc=clotfje,dc=net",
        'password' => 'fjeclot', // In production, use local.php for sensitive data
        'bindRequiresDn' => true,
        'accountDomainName' => 'clotfje.net',
        'baseDn' => 'dc=clotfje,dc=net',
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../../module/Application/view/layout/layout.php',
            'application/index/index' => __DIR__ . '/../../module/Application/view/application/index/index.php',
            'error/404'               => __DIR__ . '/../../module/Application/view/error/404.php',
            'error/index'             => __DIR__ . '/../../module/Application/view/error/index.php',
        ],
        'template_path_stack' => [
            __DIR__ . '/../../module/Application/view',
        ],
    ],
    'session_config' => [
        'cookie_lifetime' => 3600,
        'gc_maxlifetime'  => 3600,
    ],
    'session_storage' => [
        'type' => Laminas\Session\Storage\SessionArrayStorage::class,
    ],
    'session_containers' => [
        'ContainerNamespace',
    ],
];