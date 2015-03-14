<?php

namespace cobe\PaginasBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicacionType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('metadatos')
            ->add('fechaArchiva')
            ->add('idexada')
            ->add('categoriaPublicacion')
            ->add('estadoPublicacion')
            ->add('tipoPublicacion')
            ->add('plantilla')
            ->add('autor')
            ->add('grupoEditor')
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
        $resolver->setDefaults(array(
            'data_class' => 'cobe\PaginasBundle\Entity\Publicacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'publicacion';
    }
}
