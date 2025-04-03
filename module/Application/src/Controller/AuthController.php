<?php

<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;
use Application\Form\LoginForm;
use Application\Service\LdapService;

class AuthController extends AbstractActionController
{
    private $ldapService;
    
    public function __construct(LdapService $ldapService)
    {
        $this->ldapService = $ldapService;
    }
    
    public function loginAction()
    {
        $form = new LoginForm();
        
        // Check if user is already logged in
        $session = new Container('user');
        if (isset($session->admin) && !empty($session->admin)) {
            return $this->redirect()->toRoute('home', ['action' => 'menu']);
        }
        
        return new ViewModel([
            'form' => $form
        ]);
    }
    
    public function authenticateAction()
    {
        // Only allow POST requests
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('auth', ['action' => 'login']);
        }
        
        $form = new LoginForm();
        $form->setData($this->getRequest()->getPost());
        
        if (!$form->isValid()) {
            return new ViewModel([
                'form' => $form,
                'error' => 'Invalid form data'
            ]);
        }
        
        $data = $form->getData();
        $username = $data['username'];
        $password = $data['password'];
        
        try {
            // Attempt authentication
            $result = $this->ldapService->authenticate($username, $password);
            
            if ($result) {
                // Authentication successful - create session
                $session = new Container('user');
                $session->admin = $username;
                
                // Redirect to menu
                return $this->redirect()->toRoute('home', ['action' => 'menu']);
            } else {
                // Authentication failed
                return new ViewModel([
                    'form' => $form,
                    'error' => 'Authentication failed. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return new ViewModel([
                'form' => $form,
                'error' => 'Authentication error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function logoutAction()
    {
        // Destroy session
        $session = new Container('user');
        $session->getManager()->getStorage()->clear('user');
        
        // Clear session cookie
        $sessionConfig = $this->getEvent()->getApplication()->getServiceManager()->get('config')['session_config'];
        if (isset($sessionConfig['cookie_lifetime'])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        
        // Redirect to home page
        return $this->redirect()->toRoute('home');
    }
}
    session_start();
    session_unset();
    $cookie_sessio = session_get_cookie_params();
    setcookie("PHPSESSID","",time()-3600,$cookie_sessio['path'], $cookie_sessio['domain'], $cookie_sessio['secure'], $cookie_sessio['httponly']); //Neteja cookie de sessió
    session_destroy();
    header("Location: index.php");
?>

<?php
    require 'vendor/autoload.php';
	use Laminas\Ldap\Ldap;

	ini_set('display_errors', 0);
	if ($_POST['cts'] && $_POST['adm']){
	   $opcions = [
            'host' => 'zend-ceriam.clotfje.net',
		    'username' => "cn=admin,dc=clotfje,dc=net",
   		    'password' => 'fjeclot',
   		    'bindRequiresDn' => true,
		    'accountDomainName' => 'clotfje.net',
   		    'baseDn' => 'dc=clotfje,dc=net',
       ];	
	   $ldap = new Ldap($opcions);
	   $dn='cn='.$_POST['adm'].',dc=clotfje,dc=net';
	   $ctsnya=$_POST['cts'];
	   try{
	       $ldap->bind($dn,$ctsnya);
	       session_start();
	       $_SESSION['adm']=$_POST['adm'];	 
	       header("location: menu.php");
	   } catch (Exception $e){
	       echo "<b>Contrasenya incorrecta</b><br><br>";	       
	   }
	}
?>
<html>
	<head>
		<title>
			AUTENTICACIÓ AMB LDAP 
		</title>
	</head>
	<body>
		<a href="https://zend-ceriam.clotfje.net/autent/index.php">Torna a la pàgina inicial</a>
	</body>
</html>
