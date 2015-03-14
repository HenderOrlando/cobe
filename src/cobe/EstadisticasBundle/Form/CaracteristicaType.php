<?php

namespace cobe\EstadisticasBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CaracteristicaType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        /*$builder
            ->add('estadisticasCaracteristica')
        ;*/

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\EstadisticasBundle\Entity\Caracteristica'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'caracteristica';
    }
}
