<?php
namespace Application\Service;

use Laminas\Ldap\Ldap;
use Laminas\Ldap\Exception\LdapException;
use Laminas\Ldap\Attribute;
use Laminas\Ldap\Filter;

class LdapService
{
    private $ldapConfig;
    private $ldap;
    
    public function __construct(array $ldapConfig)
    {
        $this->ldapConfig = $ldapConfig;
        $this->ldap = new Ldap($this->ldapConfig);
    }
    
    /**
     * Authenticate user against LDAP
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $dn = 'cn=' . $username . ',' . $this->ldapConfig['baseDn'];
        
        try {
            $this->ldap->bind($dn, $password);
            return true;
        } catch (LdapException $e) {
            return false;
        }
    }
    
    /**
     * Get user data from LDAP
     *
     * @param string $uid
     * @param string $ou
     * @return array|null
     * @throws \Exception
     */
    public function getUser($uid, $ou)
    {
        $dn = 'uid=' . $uid . ',ou=' . $ou . ',' . $this->ldapConfig['baseDn'];
        
        try {
            $this->ldap->bind($this->ldapConfig['username'], $this->ldapConfig['password']);
            return $this->ldap->getEntry($dn);
        } catch (LdapException $e) {
            throw new \Exception('User not found: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all users from LDAP
     *
     * @return array
     * @throws \Exception
     */
    public function getAllUsers()
    {
        try {
            $this->ldap->bind($this->ldapConfig['username'], $this->ldapConfig['password']);
            
            $filter = Filter::equals('objectClass', 'posixAccount');
            $options = [
                'filter' => $filter,
                'attributes' => ['uid', 'ou', 'cn'],
            ];
            
            $users = [];
            $result = $this->ldap->search($filter, $this->ldapConfig['baseDn'], Ldap::SEARCH_SCOPE_SUB, ['uid', 'ou', 'cn']);
            
            foreach ($result as $item) {
                if (isset($item['uid'][0])) {
                    $users[] = [
                        'uid' => $item['uid'][0],
                        'ou' => isset($item['ou'][0]) ? $item['ou'][0] : '',
                        'cn' => isset($item['cn'][0]) ? $item['cn'][0] : '',
                        'dn' => $item['dn'],
                    ];
                }
            }
            
            return $users;
        } catch (LdapException $e) {
            throw new \Exception('Error retrieving users: ' . $e->getMessage());
        }
    }
    
    /**
     * Create new user in LDAP
     *
     * @param array $userData
     * @return bool
     * @throws \Exception
     */
    public function createUser(array $userData)
    {
        try {
            $this->ldap->bind($this->ldapConfig['username'], $this->ldapConfig['password']);
            
            $dn = 'uid=' . $userData['uid'] . ',ou=' . $userData['ou'] . ',' . $this->ldapConfig['baseDn'];
            
            $entry = [
                'objectClass' => ['top', 'person', 'organizationalPerson', 'inetOrgPerson', 'posixAccount'],
                'uid' => $userData['uid'],
                'cn' => $userData['cn'],
                'sn' => $userData['cn'], // Using cn as sn if not provided
                'uidNumber' => $userData['uidNumber'],
                'gidNumber' => $userData['gidNumber'],
                'homeDirectory' => $userData['homeDirectory'] . $userData['uid'],
                'loginShell' => $userData['loginShell'],
            ];
            
            // Add optional attributes if they exist
            if (!empty($userData['postalAddress'])) {
                $entry['postalAddress'] = $userData['postalAddress'];
            }
            
            if (!empty($userData['telephoneNumber'])) {
                $entry['telephoneNumber'] = $userData['telephoneNumber'];
            }
            
            if (!empty($userData['title'])) {
                $entry['title'] = $userData['title'];
            }
            
            if (!empty($userData['description'])) {
                $entry['description'] = $userData['description'];
            }
            
            $this->ldap->add($dn, $entry);
            return true;
        } catch (LdapException $e) {
            throw new \Exception('Error creating user: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete user from LDAP
     *
     * @param string $uid
     * @param string $ou
     * @return bool
     * @throws \Exception
     */
    public function deleteUser($uid, $ou)
    {
        try {
            $this->ldap->bind($this->ldapConfig['username'], $this->ldapConfig['password']);
            
            $dn = 'uid=' . $uid . ',ou=' . $ou . ',' . $this->ldapConfig['baseDn'];
            $this->ldap->delete($dn);
            
            return true;
        } catch (LdapException $e) {
            throw new \Exception('Error deleting user: ' . $e->getMessage());
        }
    }
    
    /**
     * Modify user attributes in LDAP
     *
     * @param string $uid
     * @param string $ou
     * @param string $attribute
     * @param string $value
     * @return bool
     * @throws \Exception
     */
    public function modifyUser($uid, $ou, $attribute, $value)
    {
        try {
            $this->ldap->bind($this->ldapConfig['username'], $this->ldapConfig['password']);
            
            $dn = 'uid=' . $uid . ',ou=' . $ou . ',' . $this->ldapConfig['baseDn'];
            
            // Check if the attribute is valid for modification
            $validAttributes = [
                'cn',
                'postalAddress',
                'telephoneNumber',
                'title',
                'description',
            ];
            
            if (!in_array($attribute, $validAttributes)) {
                throw new \Exception('Invalid attribute specified for modification');
            }
            
            // Perform the modification
            $this->ldap->update($dn, [$attribute => $value]);
            
            return true;
        } catch (LdapException $e) {
            throw new \Exception('Error modifying user: ' . $e->getMessage());
        }
    }
}