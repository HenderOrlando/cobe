<?php

namespace cobe\CommonBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtiquetaType extends ObjectType
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
            ->add('estadisticas')
            ->add('estudiantes')
            ->add('grupos')
            ->add('ofertasLaborales')
            ->add('publicaciones')
        ;
        if(is_string($this->action)){
            $builder->setAction($this->action);
        }
        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'data_class' => 'cobe\CommonBundle\Entity\Etiqueta'
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
        return 'etiqueta';
    }
}
