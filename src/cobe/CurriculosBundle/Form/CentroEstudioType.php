<?php

namespace cobe\CurriculosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CentroEstudioType extends AbstractType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\CurriculosBundle\Entity\CentroEstudio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_curriculosbundle_centroestudio';
    }
}
