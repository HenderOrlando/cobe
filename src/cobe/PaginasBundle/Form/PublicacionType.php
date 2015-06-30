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
            ->add('archivos')
            ->add('autor')
            ->add('categorias')
            ->add('comentarios')
            ->add('fechaArchiva', null, array(
                'widget' => 'single_text'
            ))
            ->add('estado')
            ->add('grupoEditor')
            ->add('indexada')
            ->add('metadatos')
            ->add('plantilla')
            ->add('idexada')
            ->add('tipo')
            ->add('ofertasLaborales')
            ->add('votaciones')
            ->add('etiquetas')
            ->add('plantilla')
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
