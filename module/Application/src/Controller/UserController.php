<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\UserSearchForm;
use Application\Form\UserCreateForm;
use Application\Form\UserDeleteForm;
use Application\Form\UserModifyForm;
use Application\Service\LdapService;

class UserController extends AbstractActionController
{
    private $ldapService;
    
    public function __construct(LdapService $ldapService)
    {
        $this->ldapService = $ldapService;
    }
    
    public function indexAction()
    {
        // Redirect to view action by default
        return $this->redirect()->toRoute('user', ['action' => 'view']);
    }
    
    public function viewAction()
    {
        $form = new UserSearchForm();
        $user = null;
        $error = null;
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                try {
                    $user = $this->ldapService->getUser($data['uid'], $data['ou']);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        return new ViewModel([
            'form' => $form,
            'user' => $user,
            'error' => $error
        ]);
    }
    
    public function listAction()
    {
        $users = [];
        $error = null;
        
        try {
            $users = $this->ldapService->getAllUsers();
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        
        return new ViewModel([
            'users' => $users,
            'error' => $error
        ]);
    }
    
    public function createAction()
    {
        $form = new UserCreateForm();
        $success = false;
        $error = null;
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                try {
                    $success = $this->ldapService->createUser($data);
                    if ($success) {
                        $form = new UserCreateForm(); // Reset form on success
                    }
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        return new ViewModel([
            'form' => $form,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    public function deleteAction()
    {
        $form = new UserDeleteForm();
        $success = false;
        $error = null;
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                try {
                    $success = $this->ldapService->deleteUser($data['uid'], $data['ou']);
                    if ($success) {
                        $form = new UserDeleteForm(); // Reset form on success
                    }
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        return new ViewModel([
            'form' => $form,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    public function modifyAction()
    {
        $searchForm = new UserSearchForm();
        $modifyForm = null;
        $user = null;
        $success = false;
        $error = null;
        
        // First step: Search for the user
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('action') === 'search') {
                $searchForm->setData($this->getRequest()->getPost());
                
                if ($searchForm->isValid()) {
                    $data = $searchForm->getData();
                    
                    try {
                        $user = $this->ldapService->getUser($data['uid'], $data['ou']);
                        if ($user) {
                            $modifyForm = new UserModifyForm();
                            $modifyForm->setData([
                                'uid' => $data['uid'],
                                'ou' => $data['ou'],
                                'cn' => $user['cn'][0] ?? '',
                                'postalAddress' => $user['postalAddress'][0] ?? '',
                                'telephoneNumber' => $user['telephoneNumber'][0] ?? '',
                                'title' => $user['title'][0] ?? '',
                                'description' => $user['description'][0] ?? '',
                            ]);
                        }
                    } catch (\Exception $e) {
                        $error = $e->getMessage();
                    }
                }
            } 
            // Second step: Modify the user
            elseif ($this->getRequest()->getPost('action') === 'modify') {
                $modifyForm = new UserModifyForm();
                $modifyForm->setData($this->getRequest()->getPost());
                
                if ($modifyForm->isValid()) {
                    $data = $modifyForm->getData();
                    
                    try {
                        $success = $this->ldapService->modifyUser(
                            $data['uid'], 
                            $data['ou'], 
                            $data['attribute'], 
                            $data[$data['attribute']]
                        );
                        
                        // Refresh user data after modification
                        $user = $this->ldapService->getUser($data['uid'], $data['ou']);
                        
                        // Update form with new data
                        $modifyForm->setData([
                            'uid' => $data['uid'],
                            'ou' => $data['ou'],
                            'cn' => $user['cn'][0] ?? '',
                            'postalAddress' => $user['postalAddress'][0] ?? '',
                            'telephoneNumber' => $user['telephoneNumber'][0] ?? '',
                            'title' => $user['title'][0] ?? '',
                            'description' => $user['description'][0] ?? '',
                            'attribute' => $data['attribute']
                        ]);
                    } catch (\Exception $e) {
                        $error = $e->getMessage();
                    }
                }
            }
        }
        
        return new ViewModel([
            'searchForm' => $searchForm,
            'modifyForm' => $modifyForm,
            'user' => $user,
            'success' => $success,
            'error' => $error
        ]);
    }
}