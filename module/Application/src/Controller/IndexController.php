<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function infoAction()
    {
        return new ViewModel();
    }
    
    public function menuAction()
    {
        $session = new Container('user');
        
        // Check if user is authenticated
        if (!isset($session->admin) || empty($session->admin)) {
            return $this->redirect()->toRoute('auth', ['action' => 'login']);
        }
        
        return new ViewModel([
            'admin' => $session->admin
        ]);
    }
}