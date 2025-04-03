<?php
namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\InputFilter\InputFilterProviderInterface;

class UserSearchForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        parent::__construct('user-search-form');
        
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
        
        // Hidden field to identify the form type on form submission
        $this->add([
            'name' => 'action',
            'type' => Element\Hidden::class,
            'attributes' => [
                'value' => 'search',
            ],
        ]);
        
        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'value' => 'Search',
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
                    [
                        'name' => 'Regex',
                        'options' => [
                            'pattern' => '/^[a-zA-Z0-9_-]+$/',
                            'message' => 'Organizational Unit can only contain letters, numbers, underscores and hyphens',
                        ],
                    ],
                ],
            ],
        ];
    }
}