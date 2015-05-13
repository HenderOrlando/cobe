<?php

namespace cobe\CommonBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RolType extends ObjectType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder->add('herenciaRol');

        if(is_string($this->action)){
            $builder->setAction($this->action);
        }
        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\CommonBundle\Entity\Rol'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rol';
    }
}
