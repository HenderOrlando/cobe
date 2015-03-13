<?php

namespace cobe\OfertasLaboralesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfertaLaboralType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estadoOfertasLaborales')
            ->add('tipoOfertasLaborales')
            ->add('usuario')
            ->add('publicacion')
            ->add('aptitudes')
            ->add('etiquetas')
            ->add('idiomas')
            ->add('ciudades')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\OfertasLaboralesBundle\Entity\OfertaLaboral'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_ofertaslaboralesbundle_ofertalaboral';
    }
}
