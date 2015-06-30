<?php

namespace cobe\CurriculosBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProyectoType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('archivos')
            ->add('empresas')
            ->add('fechaInicio', null, array(
                'widget' => 'single_text'
            ))
            ->add('fechaFin', null, array(
                'widget' => 'single_text'
            ))
            ->add('personasProyecto')
            ->add('tipo')
        ;

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\CurriculosBundle\Entity\Proyecto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'proyecto';
    }
}
