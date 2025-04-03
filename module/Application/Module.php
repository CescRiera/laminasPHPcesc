<?php
namespace Application;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $serviceManager      = $e->getApplication()->getServiceManager();
        
        // Initialize session
        $this->bootstrapSession($e);
        
        // Check authentication for restricted pages
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkAuth'], -100);
    }
    
    public function bootstrapSession(MvcEvent $e)
    {
        $session = $e->getApplication()
                     ->getServiceManager()
                     ->get(SessionManager::class);
        $session->start();
        
        $container = new Container('initialized');
        
        if (!isset($container->init)) {
            $session->regenerateId(true);
            $container->init = 1;
        }
    }
    
    public function checkAuth(MvcEvent $e)
    {
        $route = $e->getRouteMatch()->getMatchedRouteName();
        $controller = $e->getRouteMatch()->getParam('controller');
        $action = $e->getRouteMatch()->getParam('action');
        
        // Public routes - no authentication needed
        $publicRoutes = [
            'home',
            'info',
            'auth' => ['login', 'authenticate'],
        ];
        
        // Check if current route needs authentication
        $needsAuth = true;
        
        if (in_array($route, $publicRoutes)) {
            $needsAuth = false;
        } elseif (isset($publicRoutes[$route]) && in_array($action, $publicRoutes[$route])) {
            $needsAuth = false;
        }
        
        // If authentication is needed, check session
        if ($needsAuth) {
            $session = new Container('user');
            
            if (!isset($session->admin) || empty($session->admin)) {
                // Redirect to login page
                $url = $e->getRouter()->assemble(
                    ['action' => 'login'],
                    ['name' => 'auth']
                );
                
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
            }
        }
    }
}