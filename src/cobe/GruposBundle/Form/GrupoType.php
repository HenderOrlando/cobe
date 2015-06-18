<?php

namespace cobe\GruposBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GrupoType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('plantilla')
        ;

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'data_class' => 'cobe\GruposBundle\Entity\Grupo'
        );
        if($this->dataClass){
            $defaults = array(
                'data_class' => $this->dataClass
            );
        }
        $resolver->setDefaults($defaults);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grupo';
    }
}
