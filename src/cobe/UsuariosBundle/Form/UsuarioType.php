<?php

namespace cobe\UsuariosBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $this->getBuilderUsuarioForm($builder, $options);

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }

    public function getBuilderUsuarioForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('archivos')
            ->add('clave')
            ->add('comentarios')
            ->add('email')
            ->add('estado')
            ->add('plantilla')
            ->add('rol')
            ->add('solicitados')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\UsuariosBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'usuario';
    }
}
