<?php
namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\InputFilter\InputFilterProviderInterface;

class UserCreateForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        parent::__construct('user-create-form');
        
        $this->add([
            'name' => 'uid',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'User ID (uid)',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'uid',
                'class' => 'form-control',
            ],
        ]);
        
        $this->add([
            'name' => 'ou',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Organizational Unit (ou)',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'ou',
                'class' => 'form-control',
            ],
        ]);
        
        $this->add([
            'name' => 'uidNumber',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'User ID Number',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'uidNumber',
                'class' => 'form-control',
                'min' => '1000',
            ],
        ]);
        
        $this->add([
            'name' => 'gidNumber',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Group ID Number',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'gidNumber',
                'class' => 'form-control',
                'min' => '1000',
            ],
        ]);
        
        $this->add([
            'name' => 'homeDirectory',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Home Directory',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'homeDirectory',
                'class' => 'form-control',
                'value' => '/home/',
            ],
        ]);
        
        $this->add([
            'name' => 'loginShell',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Login Shell',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'loginShell',
                'class' => 'form-control',
                'value' => '/bin/bash',
            ],
        ]);
        
        $this->add([
            'name' => 'cn',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Full Name (cn)',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'cn',
                'class' => 'form-control',
            ],
        ]);
        
        $this->add([
            'name' => 'postalAddress',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'Postal Address',
            ],
            'attributes' => [
                'id' => 'postalAddress',
                'class' => 'form-control',
                'rows' => 3,
            ],
        ]);
        
        $this->add([
            'name' => 'telephoneNumber',
            'type' => Element\Tel::class,
            'options' => [
                'label' => 'Telephone Number',
            ],
            'attributes' => [
                'id' => 'telephoneNumber',
                'class' => 'form-control',
            ],
        ]);
        
        $this->add([
            'name' => 'title',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Job Title',
            ],
            'attributes' => [
                'id' => 'title',
                'class' => 'form-control',
            ],
        ]);
        
        $this->add([
            'name' => 'description',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'Description',
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'form-control',
                'rows' => 3,
            ],
        ]);
        
        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'value' => 'Create User',
                'id' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
        
        $this->add([
            'name' => 'reset',
            'type' => Element\Reset::class,
            'attributes' => [
                'value' => 'Reset',
                'id' => 'reset',
                'class' => 'btn btn-secondary',
            ],
        ]);
    }
    
    public function getInputFilterSpecification()
    {
        return [
            'uid' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                    [
                        'name' => 'Regex',
                        'options' => [
                            'pattern' => '/^[a-zA-Z0-9_-]+$/',
                            'message' => 'User ID can only contain letters, numbers, underscores and hyphens',
                        ],
                    ],
                ],
            ],
            'ou' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ],
            'uidNumber' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                    ['name' => 'ToInt'],
                ],
                'validators' => [
                    [
                        'name' => 'Digits',
                    ],
                    [
                        'name' => 'GreaterThan',
                        'options' => [
                            'min' => 1000,
                            'inclusive' => true,
                        ],
                    ],
                ],
            ],
            'gidNumber' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                    ['name' => 'ToInt'],
                ],
                'validators' => [
                    [
                        'name' => 'Digits',
                    ],
                    [
                        'name' => 'GreaterThan',
                        'options' => [
                            'min' => 1000,
                            'inclusive' => true,
                        ],
                    ],
                ],
            ],
            'homeDirectory' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ],
            'loginShell' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ],
            'cn' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ],
            'postalAddress' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'telephoneNumber' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'title' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'description' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
        ];
    }
}