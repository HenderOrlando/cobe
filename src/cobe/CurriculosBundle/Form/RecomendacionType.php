<?php

namespace cobe\CurriculosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecomendacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('fechaCreado')
            ->add('recomienda')
            ->add('recomendado')
            ->add('tipo')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\CurriculosBundle\Entity\Recomendacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_curriculosbundle_recomendacion';
    }
}
