<?php

namespace cobe\MensajesBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MensajeType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('estado')
            ->add('plantilla')
            ->add('usuario')
            ->add('destinatarios')
            ->add('archivos')
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
            'data_class' => 'cobe\MensajesBundle\Entity\Mensaje'
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
        return 'mensaje';
    }
}
