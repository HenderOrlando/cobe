<?php

namespace cobe\ColeccionesBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('url')
            ->add('fullUrl')
            ->add('size')
            ->add('ext')
            ->add('estado')
            ->add('tipo')
            ->add('comentarios')
            ->add('etiquetas')
        ;

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'data_class' => 'cobe\ColeccionesBundle\Entity\Archivo'
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
        return 'archivo';
    }
}
