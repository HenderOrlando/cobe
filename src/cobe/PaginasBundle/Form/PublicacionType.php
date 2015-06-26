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
            ->add('autor')
            ->add('categorias')
            ->add('fechaArchiva')
            ->add('estadoPublicacion')
            ->add('grupoEditor')
            ->add('indexada')
            ->add('metadatos')
            ->add('plantilla')
            ->add('idexada')
            ->add('tipoPublicacion')
            ->add('ofertasLaborales')
            ->add('comentarios')
            ->add('votacion')
            ->add('archivosPublicacion')
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
