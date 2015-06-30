<?php

namespace cobe\UsuariosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RepresentanteEmpresaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actual')
            ->add('empresa')
            ->add('fechaInicio', null, array(
                'widget' => 'single_text'
            ))
            ->add('fechaFin', null, array(
                'widget' => 'single_text'
            ))
            ->add('persona')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\UsuariosBundle\Entity\RepresentanteEmpresa'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'representanteempresa';
    }
}
