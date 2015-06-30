<?php

namespace cobe\GruposBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VotacionType extends ObjectType
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
            ->add('fechaFin', null, array(
                'widget' => 'single_text'
            ))
            ->add('opciones')
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
            'data_class' => 'cobe\GruposBundle\Entity\Votacion'
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
        return 'votacion';
    }
}
