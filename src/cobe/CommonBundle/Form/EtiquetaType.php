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

        /*$builder
            ->add('empresas')
            ->add('grupos')
            ->add('ofertasLaborales')
            ->add('publicaciones')
            ->add('archivos')
            ->add('estadisticasEtiqueta')
        ;*/
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
        $resolver->setDefaults(array(
            'data_class' => 'cobe\CommonBundle\Entity\Etiqueta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'etiqueta';
    }
}