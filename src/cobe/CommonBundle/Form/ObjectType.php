<?php

namespace cobe\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class ObjectType extends AbstractType
{
    final public function hasVerbHttp($test){
        return in_array($test, array(
            'GET',
            'PUT',
            'POST',
            'DELETE',
            'PATCH',
            'OPTIONS',
        ));
    }
    final public function addSubmit(FormBuilderInterface $builder){
        if(empty($this->buttons)){
            $this->buttons = array('submit'=>array());
        }
        foreach($this->buttons as $name => $options){
            $builder->add($name,'submit',$options);
        }
    }
    protected $action = NULL;
    protected $method = NULL;
    protected $buttons = NULL;

    public function __construct($action = NULL, $method = NULL, $buttons = array()){
        $this->action = $action;
        $this->buttons = $buttons;
        $this->method = $this->hasVerbHttp($method)?$method:'POST';
    }

    public function addObjectForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }
}
