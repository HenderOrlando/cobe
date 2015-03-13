<?php

namespace cobe\EstadisticasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstadisticaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo')
            ->add('estado')
            ->add('etiquetas')
            ->add('caracteristicas')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\EstadisticasBundle\Entity\Estadistica'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_estadisticasbundle_estadistica';
    }
}
