<?php

namespace cobe\PaginasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
        return 'cobe_paginasbundle_publicacion';
    }
}
