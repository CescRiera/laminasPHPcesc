<?php
namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\InputFilter\InputFilterProviderInterface;

class UserModifyForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        parent::__construct('user-modify-form');
        
        $this->add([
            'name' => 'uid',
            'type' => Element\Hidden::class,
            'attributes' => [
                'required' => true,
                'id' => 'uid',
            ],
        ]);
        
        $this->add([
            'name' => 'ou',
            'type' => Element\Hidden::class,
            'attributes' => [
                'required' => true,
                'id' => 'ou',
            ],
        ]);
        
        $this->add([
            'name' => 'attribute',
            'type' => Element\Radio::class,
            'options' => [
                'label' => 'Attribute to modify',
                'value_options' => [
                    'cn' => 'Full Name (cn)',
                    'postalAddress' => 'Postal Address',
                    'telephoneNumber' => 'Telephone Number',
                    'title' => 'Job Title',
                    'description' => 'Description',
                ],
            ],
            'attributes' => [
                'required' => true,
                'id' => 'attribute',
                'class' => 'form-check-input',
            ],
        ]);
        
        $this->add([
            'name' => 'cn',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Full Name (cn)',
            ],
            'attributes' => [
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
        
        // Hidden field to identify the form type on form submission
        $this->add([
            'name' => 'action',
            'type' => Element\Hidden::class,
            'attributes' => [
                'value' => 'modify',
            ],
        ]);
        
        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'value' => 'Modify User',
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
            ],
            'ou' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'attribute' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cn' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
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