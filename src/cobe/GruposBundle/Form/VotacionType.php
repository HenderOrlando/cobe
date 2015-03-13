<?php

namespace cobe\GruposBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VotacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('opciones')
            ->add('fechaFin')
            ->add('opcionSeleccionada')
            ->add('estado')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\GruposBundle\Entity\Votacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_gruposbundle_votacion';
    }
}
